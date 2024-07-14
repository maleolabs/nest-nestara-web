<?php

namespace App\Http\Controllers;

use App\Services\DomainServices\PerizinanService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected PerizinanService $perizinanService;

    public function __construct(PerizinanService $perizinanService)
    {
        $this->perizinanService = $perizinanService;
    }
    
    public function previewIzin(Request $request, $izin_id)
    {
        $izin_id = (int) base64_decode($izin_id);
        return response()->view('dashboard.preview-izin', [
            'izin' => $this->perizinanService->find($izin_id)
        ]);
    }
    
}