<?php

namespace App\Http\Controllers\BackOffice;

use App\Charts\StatistikJenisPerizinan;
use App\Http\Controllers\Controller;
use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected PerizinanService $perizinanService;

    protected EncryptionService $encryptionService;

    protected JenisPerizinanService $jenisPerizinanService;

    public function __construct(
        PerizinanService $perizinanService,
        EncryptionService $encryptionService,
        JenisPerizinanService $jenisPerizinanService
    ) {
        $this->perizinanService = $perizinanService;
        $this->encryptionService = $encryptionService;
        $this->jenisPerizinanService = $jenisPerizinanService;
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

    public function rekomendasi()
    {
        return response()->view('back-office.rekomendasi');
    }

    public function penetapan()
    {
        return response()->view('back-office.penetapan');
    }

    public function downloadSurat(Request $request)
    {
        $id = $request->id;
        try {
            $id = $this->encryptionService->decryptId($id);
        } catch (DecryptException $e) {
            abort(403);
        }

        $izin = $this->perizinanService->find($id);
        if (!$izin)
            abort(404);

        $templateView = $this->jenisPerizinanService->getPdfTemplate($izin->jenis_perizinan_id);
        if (!$templateView)
            abort(404);

        $title = "IZIN-{$izin->no_pendaftaran}";

        return view($templateView, [
            'title' => $title
        ]);
    }
    
}