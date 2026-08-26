<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PemohonRequest;
use App\Http\Resources\PemohonResource;
use App\Services\DomainServices\PemohonService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PemohonController extends Controller
{

    private PemohonService $pemohonService;

    public function __construct(PemohonService $pemohonService)
    {
        $this->pemohonService = $pemohonService;
    }

    public function store(PemohonRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $authUser = Auth::guard('api')->user();
            $pemohon = $authUser->pemohon;

            if ($pemohon) {
                // user sudah punya pemohon → jalur update biasa
                $savedData = $this->pemohonService->update($pemohon->id, $data);
                $statusCode = Response::HTTP_OK;
            } else {
                // jalur create: cek dulu pemohon yatim dengan nomor_id yang sama.
                // Yatim = pemilik akun lamanya sudah hapus akun (user_id NULL).
                // Kalau ketemu, adopsi baris lama (bukan buat baru) supaya
                // seluruh riwayat izin di balik pemohon tersebut tetap menyatu.
                $orphan = $this->pemohonService->findOrphanByNomorId($data['nomor_id']);

                if ($orphan) {
                    // ADOPT: kaitkan ke akun ini lalu perbarui datanya.
                    // Respons 200 (BUKAN 201) karena baris tidak baru dibuat.
                    // Catatan kontrak: mobile/web hanya membaca body id,
                    // jadi bentuk body tetap sama seperti jalur lain.
                    $savedData = $this->pemohonService->adopt($orphan->id, $authUser->id, $data);
                    $statusCode = Response::HTTP_OK;
                } else {
                    $savedData = $this->pemohonService->save($data, $authUser->id);
                    $statusCode = Response::HTTP_CREATED;
                }
            }

            return response()->json($savedData->id, $statusCode);
        } catch (QueryException $e) {
            // nomor_id milik user AKTIF lain → pelanggaran UNIQUE
            // (SQLSTATE 23000 / MySQL error 1062). Bukan error server,
            // balas 422 dengan bentuk errors konsisten dengan validasi FormRequest.
            if ($this->isDuplicateEntry($e)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'nomor_id' => ['Nomor identitas sudah terdaftar pada akun lain.']
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            Log::channel('app')->error('Tidak dapat menyimpan data pemohon', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data pemohon'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            Log::channel('app')->error('Tidak dapat menyimpan data pemohon', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data pemohon'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mendeteksi pelanggaran constraint UNIQUE dari QueryException:
     * SQLSTATE 23000 (integrity constraint violation) atau
     * MySQL driver error code 1062 (duplicate entry).
     */
    private function isDuplicateEntry(QueryException $e): bool
    {
        return $e->getCode() == 23000 || ($e->errorInfo[1] ?? null) == 1062;
    }

    public function find(): JsonResponse
    {
        $authUser = Auth::guard('api')->user();
        $pemohon = $authUser->pemohon;
        if (empty($pemohon)) {
            return response()->json([
                'success' => false,
                'message' => 'pemohon not found',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json(new PemohonResource($pemohon), Response::HTTP_OK);
    }

}
