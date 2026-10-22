<?php

namespace App\Http\Controllers\BackOffice;

use App\Charts\StatistikJenisPerizinan;
use App\Http\Controllers\Controller;
use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PemohonService;
use App\Jobs\GeneratePermitLetter;
use App\Models\PermitLetterGeneration;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SuratService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{

    protected PerizinanService $perizinanService;

    protected EncryptionService $encryptionService;

    protected JenisPerizinanService $jenisPerizinanService;

    protected EntriDataService $entriDataService;

    protected SuratService $suratService;

    protected PemohonService $pemohonService;

    public function __construct(
        PerizinanService $perizinanService,
        EncryptionService $encryptionService,
        JenisPerizinanService $jenisPerizinanService,
        EntriDataService $entriDataService,
        SuratService $suratService,
        PemohonService $pemohonService
    ) {
        $this->perizinanService = $perizinanService;
        $this->encryptionService = $encryptionService;
        $this->jenisPerizinanService = $jenisPerizinanService;
        $this->entriDataService = $entriDataService;
        $this->suratService = $suratService;
        $this->pemohonService = $pemohonService;
    }
    
    public function index(StatistikJenisPerizinan $statistikJenisPerizinan)
    {
        return response()->view('back-office.index', [
            'all_izin' => $this->perizinanService->getAll('', [], [], null, null, 'DESC', 10),
            'statistikJenisPerizinan' => $statistikJenisPerizinan->build(),
            'all_pemohon' => $this->pemohonService->getAll('', 20)
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
            abort(Response::HTTP_FORBIDDEN);
        }

        $izin = $this->perizinanService->find($id);
        if (!$izin)
            abort(Response::HTTP_NOT_FOUND, 'Izin not found');

        $generation = DB::transaction(function () use ($izin) {
            $generation = PermitLetterGeneration::where('izin_id', $izin->id)->latest()->first();
            if ($generation && in_array($generation->status, [PermitLetterGeneration::QUEUED, PermitLetterGeneration::PROCESSING, PermitLetterGeneration::COMPLETED], true)) return $generation;
            return PermitLetterGeneration::create(['izin_id' => $izin->id, 'status' => PermitLetterGeneration::QUEUED]);
        });
        if ($generation->status === PermitLetterGeneration::QUEUED) GeneratePermitLetter::dispatch($generation->id)->afterCommit();
        return response()->json(['id' => $generation->id, 'status' => $generation->status], Response::HTTP_ACCEPTED);
    }

    public function letterStatus(int $generationId)
    {
        $generation = PermitLetterGeneration::findOrFail($generationId);
        abort_unless($generation->izin_id, Response::HTTP_FORBIDDEN);
        return response()->json(['id' => $generation->id, 'status' => $generation->status, 'error' => $generation->error]);
    }

    public function downloadCompletedLetter(int $generationId)
    {
        $generation = PermitLetterGeneration::findOrFail($generationId);
        abort_unless($generation->status === PermitLetterGeneration::COMPLETED && $generation->path, Response::HTTP_NOT_FOUND);
        abort_unless(Storage::disk('local')->exists($generation->path), Response::HTTP_NOT_FOUND);
        return Storage::disk('local')->download($generation->path, "IZIN-{$generation->izin_id}.pdf");
    }
    
}
