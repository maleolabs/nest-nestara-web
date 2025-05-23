<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DomainServices\EntriDataService;
use Illuminate\Http\JsonResponse;

class DataEntriController extends Controller
{

    private EntriDataService $entriDataService;

    public function __construct(EntriDataService $entriDataService)
    {
        $this->entriDataService = $entriDataService;
    }

    public function getByJenisPerizinanId(int $perizinan_id): JsonResponse
    {
        $data = $this->entriDataService->getByJenisPerizinanId($perizinan_id);

        return response()->json($data, 200);
    }
    
}