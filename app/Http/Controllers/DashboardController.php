<?php

namespace App\Http\Controllers;

use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected PerizinanService $perizinanService;

    protected EncryptionService $encryptionService;

    public function __construct(
        PerizinanService $perizinanService,
        EncryptionService $encryptionService)
    {
        $this->perizinanService = $perizinanService;
        $this->encryptionService = $encryptionService;
    }
    
    public function previewIzin(Request $request, $izin_id)
    {
        try {
            $izin_id = (int) $this->encryptionService->decryptId($izin_id);
        } catch (DecryptException $e) {
            abort(500, $e->getMessage());
        }
        $izin = $this->perizinanService->find($izin_id);
        if (!$izin)
            return redirect()->route('dashboard');
        return response()->view('dashboard.preview-izin', [
            'izin' => $izin
        ]);
    }

    public function confirmIzin(Request $request)
    {
        $izin = null;
        $message = null;
        $izin_id = $request->izin_id;
        try {
            $izin_id = $this->encryptionService->decryptId($izin_id);
            $izin = $this->perizinanService->find($izin_id);
        } catch (DecryptException $e) {
            $message = 'Izin tidak terdaftar';
        }

        return response()->view('confirm-izin', compact('izin', 'message'));
    }
    
}