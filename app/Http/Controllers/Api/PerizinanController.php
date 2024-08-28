<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DaftarIzinRequest;
use App\Http\Resources\IzinResource;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerizinanController extends Controller
{

    private PerizinanService $perizinanService;

    public function __construct(PerizinanService $perizinanService)
    {
        $this->perizinanService = $perizinanService;
    }

    public function get()
    {
        $current_user = Auth::guard('api')->user();
        $data = $current_user->pemohon ? $current_user->pemohon->izin : null;

        return response()->json(($data ? IzinResource::collection($data) : []), 200);
    }
    
    public function daftarIzin(DaftarIzinRequest $request)
    {

    }
    
}