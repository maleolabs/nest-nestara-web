<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JenisPerizinanResource;
use App\Http\Resources\SyaratIzinResource;
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
        return response()->json(new JenisPerizinanResource($data), 200);
    }

    public function getAll(): JsonResponse
    {
        $search = request()->query('search', '');
        $page = request()->query('page', 1);
        $limit = request()->query('limit', 10);
        $onlyTemplateExists = request()->query('available', false);

        $data = $this->jenisPerizinanService->getAllPaginate($search, $page, $limit);
        $idsAvailabele = $this->jenisPerizinanService->getIdsWhereTemplateExists();
        $dataWithTemplateExists = array_filter($data, function($item) use ($idsAvailabele) {
            return in_array($item['id'], $idsAvailabele);
        });

        return response()->json(JenisPerizinanResource::collection($onlyTemplateExists === 'true' ? $dataWithTemplateExists : $data), 200);
    }

    public function getPersyaratan($id): JsonResponse
    {
        $data = $this->jenisPerizinanService->getDataPersyaratan($id);

        return response()->json(SyaratIzinResource::collection($data), 200);
    }

}