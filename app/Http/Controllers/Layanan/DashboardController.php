<?php

namespace App\Http\Controllers\Layanan;

use App\Charts\StatistikJenisPerizinan;
use App\Http\Controllers\Controller;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected PerizinanService $perizinanService;

    public function __construct(PerizinanService $perizinanService)
    {
        $this->perizinanService = $perizinanService;
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
            'statistikJenisPerizinan' => $statistikJenisPerizinan->build()
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