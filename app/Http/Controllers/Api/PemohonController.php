<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PemohonRequest;
use App\Http\Resources\PemohonResource;
use App\Services\DomainServices\PemohonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

        $authUser = Auth::guard('api')->user();
        $pemohon = $authUser->pemohon;

        $savedData = $pemohon ? $this->pemohonService->update($pemohon->id, $data) : $this->pemohonService->save($data, $authUser->id);
        return response()->json([
            'id' => $savedData->id
        ], Response::HTTP_CREATED);
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