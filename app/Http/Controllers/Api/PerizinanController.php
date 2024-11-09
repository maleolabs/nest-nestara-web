<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnprocessableEntityException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DaftarIzinRequest;
use App\Http\Resources\DokumenPemohonResource;
use App\Http\Resources\IzinResource;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        $current_user = Auth::guard('api')->user();
        $data = $current_user->pemohon ? $current_user->pemohon->izin : null;

        return response()->json(($data ? IzinResource::collection($data) : []), Response::HTTP_OK);
    }
    
    public function create(DaftarIzinRequest $request): JsonResponse
    {
        $data = $request->validated();
        $current_user = Auth::guard('api')->user();
        $pemohon = $current_user->pemohon;
        if (empty($pemohon)) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemohon tidak ditemukan',
                'error' => 'Data pemohon tidak ditemukan'
            ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pendaftaran izin',
                'errors' => $e->getEntityErrorMessage()
            ], Response::HTTP_BAD_REQUEST);
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
    
}