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
    
    public function jenisPerizinan(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $all_perizinan = JenisPerizinan::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'LIKE', "%{$q}%")
                        ->orWhere('kelompok_izin', 'LIKE', "%{$q}%");
                });
            })
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        return response()->view('user.jenis-perizinan', [
            'all_perizinan' => $all_perizinan,
            'q' => $q,
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