<?php

namespace App\Http\Controllers\BackOffice;

use App\Charts\StatistikJenisPerizinan;
use App\Http\Controllers\Controller;
use App\Services\DomainServices\PerizinanService;
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
        return response()->view('back-office.index', [
            'all_izin' => $this->perizinanService->getAll('', [], [], null, null, 'DESC', 10),
            'statistikJenisPerizinan' => $statistikJenisPerizinan->build(), 
        ]);
    }

    public function pendataan()
    {
        return response()->view('back-office.pendataan');
    }

    public function timTeknis()
    {
        return response()->view('back-office.tim-teknis');
    }

    public function penetapan()
    {
        return response()->view('back-office.penetapan');
    }
    
}