<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use App\Services\AppService\EncryptionService;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{

    protected EncryptionService $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }
    
    public function jenisPerizinan()
    {
        return response()->view('user.jenis-perizinan', [
            'all_perizinan' => JenisPerizinan::paginate(20)
        ]);
    }

    public function create()
    {
        return response()->view('user.create-izin');
    }

    public function semuaIzin()
    {
        return response()->view('user.izin-saya');
    }

    public function revisiIzin(Request $request)
    {
        $izin_id = $this->encryptionService->decryptId($request->route('izin_id'));
        return response()->view('user.revisi-izin', [
            'izin_id' => $izin_id
        ]);
    }

}