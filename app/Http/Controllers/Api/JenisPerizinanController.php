<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JenisPerizinanResource;
use App\Http\Resources\SyaratIzinResource;
use App\Models\JenisPerizinan;
use App\Services\DomainServices\JenisPerizinanService;
use Illuminate\Http\JsonResponse;

class JenisPerizinanController extends Controller
{
    
    private JenisPerizinanService $jenisPerizinanService;

    public function __construct(JenisPerizinanService $jenisPerizinanService) {
        $this->jenisPerizinanService = $jenisPerizinanService;
    }

    public function find(int $id): JsonResponse
    {
        $data = $this->jenisPerizinanService->find($id);
        return response()->json($data, 200);
    }

    public function getAll(): JsonResponse
    {
        $search = request()->query('search', '');
        $page = request()->query('page', 1);
        $limit = request()->query('limit', 10);

        $data = $this->jenisPerizinanService->getAllPaginate($search, $page, $limit);

        return response()->json(JenisPerizinanResource::collection($data), 200);
    }

    public function getPersyaratan($id): JsonResponse
    {
        $data = $this->jenisPerizinanService->getDataPersyaratan($id);

        return response()->json(SyaratIzinResource::collection($data), 200);
    }

}