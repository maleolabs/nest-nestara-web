<?php

namespace App\Http\Controllers\Layanan;

use App\Charts\StatistikJenisPerizinan;
use App\Http\Controllers\Controller;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected PerizinanService $perizinanService;

    protected PemohonService $pemohonService;

    public function __construct(
        PerizinanService $perizinanService,
        PemohonService $pemohonService)
    {
        $this->perizinanService = $perizinanService;
        $this->pemohonService = $pemohonService;
    }
    
    public function index(StatistikJenisPerizinan $statistikJenisPerizinan)
    {
        return response()->view('layanan.index', [
            'all_izin' => $this->perizinanService->getAll('', [
                StatusIzinEnum::DIDAFTARKAN,
                StatusIzinEnum::DITINJAU,
                StatusIzinEnum::PERUBAHAN,
                StatusIzinEnum::PENYERAHAN
            ], [], null, null, 'DESC', 10),
            'statistikJenisPerizinan' => $statistikJenisPerizinan->build(),
            'all_pemohon' => $this->pemohonService->getAll('', 20)
        ]);
    }

    public function pendaftaran()
    {
        return response()->view('layanan.pendaftaran');
    }

    public function penyerahan()
    {
        return response()->view('layanan.penyerahan');
    }

    public function pendaftaranCreate(Request $request)
    {
        $type =str_capitalize(str_replace('-', ' ', $request->input('type')));
        return response()->view('layanan.pendaftaran-create', [
            'type' => $type
        ]);
    }
    
}