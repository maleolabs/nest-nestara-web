<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DaftarIzinRequest;
use App\Http\Resources\IzinResource;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Http\JsonResponse;
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

        return response()->json(($data ? IzinResource::collection($data) : []), 200);
    }
    
    public function create(DaftarIzinRequest $request)
    {
        $data = $request->validated();

        $this->perizinanService->saveRegistration($data['jenis_perizinan_id'], $data['pemohon_id']);
    }
    
}