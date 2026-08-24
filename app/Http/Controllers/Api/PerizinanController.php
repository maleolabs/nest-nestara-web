<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnprocessableEntityException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DaftarIzinRequest;
use App\Http\Resources\DokumenPemohonResource;
use App\Http\Resources\IzinResource;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\PerizinanService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PerizinanController extends Controller
{

    private PerizinanService $perizinanService;

    private DokumenPemohonService $dokumenPemohonService;

    public function __construct(
        PerizinanService $perizinanService,
        DokumenPemohonService $dokumenPemohonService)
    {
        $this->perizinanService = $perizinanService;
        $this->dokumenPemohonService = $dokumenPemohonService;
    }

    public function find(int $id): JsonResponse
    {
        $izin = $this->perizinanService->find($id);
        if (empty($izin)) {
            return response()->json([
                'success' => false,
                'message' => 'Izin tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json(new IzinResource($izin), Response::HTTP_OK);
    }

    public function get(): JsonResponse
    {
        $page = request()->query('page', 1);
        $limit = request()->query('limit', 10);
        $offset = ($page - 1) * $limit;

        $current_user = Auth::guard('api')->user();
        $data = $current_user->pemohon 
            ? $current_user->pemohon->izin()
                ->orderBy('updated_at', 'DESC')
                ->offset($offset)
                ->limit($limit)
                ->get() 
            : null;

        return response()->json(($data ? IzinResource::collection($data) : []), Response::HTTP_OK);
    }
    
    public function create(DaftarIzinRequest $request): JsonResponse
    {
        Log::channel('app')->info('request izin', ['persyaratan' => $request->input('persyaratan')]);
        $data = $request->validated();
        $current_user = Auth::guard('api')->user();
        $pemohon = $current_user->pemohon;
        if (empty($pemohon)) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemohon tidak ditemukan',
                'error' => 'Data pemohon tidak ditemukan'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $izin = $this->perizinanService->saveRegistrationWithPersyaratan($data['jenis_perizinan_id'], $pemohon->id, $request->persyaratan ?? []);
    
            if (empty($izin)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan pendaftaran izin'
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (UnprocessableEntityException $e) {
            Log::channel('app')->error('error saving izin', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pendaftaran izin',
                'errors' => $e->getEntityErrorMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            Log::channel('app')->error('error saving izin', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pendaftaran izin'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'id' => $izin->id
        ], Response::HTTP_OK);
    }

    public function getPersyaratan(int $id): JsonResponse
    {
        $izin = $this->perizinanService->find($id);
        if (empty($izin)) {
            return response()->json([
                'success' => false,
                'message' => 'Izin tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        $allDokumen = $this->dokumenPemohonService->getAllByPemohonAndSyaratAndIzin($izin->pemohon_id, $izin->jenis_perizinan_id, $id);
        
        return response()->json(DokumenPemohonResource::collection(collect($allDokumen)), Response::HTTP_OK);
    }

    public function cancelRegistration(int $id): JsonResponse
    {
        $authUser = Auth::guard('api')->user();
        $izin = $this->perizinanService->find($id);
        if (empty($izin)) {
            return response()->json([
                'success' => false,
                'error' => 'izin tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        $description = 'Izin ini telah dibatalkan oleh ' . $authUser->username;
        $this->perizinanService->cancelRegistration($id, $description);

        return response()->json([
            'success' => true,
            'message' => $description
        ], Response::HTTP_OK);
    }
    
    public function delete(int $id): JsonResponse
    {
        $authUser = Auth::guard('api')->user();
        $izin = $this->perizinanService->find($id);
        if (empty($izin)) {
            return response()->json([
                'success' => false,
                'error' => 'izin tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }
        $this->perizinanService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'izin berhasil di hapus'
        ], Response::HTTP_OK);
    }

}