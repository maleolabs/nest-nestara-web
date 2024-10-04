<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DaftarIzinRequest;
use App\Http\Resources\IzinResource;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PerizinanController extends Controller
{

    private PerizinanService $perizinanService;

    public function __construct(PerizinanService $perizinanService)
    {
        $this->perizinanService = $perizinanService;
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

        $izin = $this->perizinanService->saveRegistration($data['jenis_perizinan_id'], $data['pemohon_id']);

        if (empty($izin)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pendaftaran izin'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'id' => $izin->id
        ], Response::HTTP_OK);
    }
    
}