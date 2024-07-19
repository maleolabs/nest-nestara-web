<?php

namespace App\Http\Controllers\BackOffice;

use App\Charts\StatistikJenisPerizinan;
use App\Http\Controllers\Controller;
use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class DashboardController extends Controller
{

    protected PerizinanService $perizinanService;

    protected EncryptionService $encryptionService;

    protected JenisPerizinanService $jenisPerizinanService;

    protected SuratService $suratService;

    public function __construct(
        PerizinanService $perizinanService,
        EncryptionService $encryptionService,
        JenisPerizinanService $jenisPerizinanService,
        SuratService $suratService
    ) {
        $this->perizinanService = $perizinanService;
        $this->encryptionService = $encryptionService;
        $this->jenisPerizinanService = $jenisPerizinanService;
        $this->suratService = $suratService;
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

        $encrypt_id = $this->encryptionService->encryptId($id);
        $qrcode_data = route('confirm.izin', ['izin_id' => $encrypt_id]);
        $qrcode_uri = generate_qrcode_as_uri($qrcode_data, 130);

        $pdf = Pdf::loadView($templateView, [
            'title' => $title,
            'izin' => $izin,
            'qrcode' => $qrcode_uri,
            'kop_surat' => $this->suratService->getKopSurat(),
            'konten' => $this->suratService->getSuratContentByJenis($izin->jenis_perizinan_id)
        ]);

        return $pdf->stream("{$title}.pdf");
    }
    
}