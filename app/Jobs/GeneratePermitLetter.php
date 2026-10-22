<?php

namespace App\Jobs;

use App\Models\PermitLetterGeneration;
use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GeneratePermitLetter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;

    public function __construct(public int $generationId) {}

    public function handle(PerizinanService $perizinan, EncryptionService $encryption, JenisPerizinanService $jenis, EntriDataService $entri, SuratService $surat): void
    {
        $generation = PermitLetterGeneration::findOrFail($this->generationId);
        if ($generation->status === PermitLetterGeneration::COMPLETED) return;
        $generation->update(['status' => PermitLetterGeneration::PROCESSING, 'error' => null]);
        try {
            $izin = $perizinan->find($generation->izin_id);
            if (!$izin) throw new \RuntimeException('Izin not found');
            $view = $jenis->getPdfTemplate($izin->jenis_perizinan_id);
            $data = $entri->getDataIzinGroupByNama($izin->id, $izin->jenis_perizinan_id);
            if (!$view || count($data) === 0) throw new \RuntimeException('Permit data incomplete');
            $id = $encryption->encryptId($izin->id);
            $pdf = Pdf::loadView($view, ['title' => "IZIN-{$izin->no_pendaftaran}", 'izin' => $izin, 'qrcode' => generate_qrcode_as_uri(route('confirm.izin', ['izin_id' => $id]), 130), 'kop_surat' => $surat->getKopSurat(), 'data_izin' => $data, 'konten' => $surat->getSuratContentByJenis($izin->jenis_perizinan_id)]);
            $path = "permit-letters/{$izin->id}/{$generation->id}.pdf";
            Storage::disk('local')->put($path, $pdf->output());
            $generation->update(['status' => PermitLetterGeneration::COMPLETED, 'path' => $path]);
            $perizinan->updateCetakIzinStatus($izin->id, null);
        } catch (\Throwable $e) {
            $generation->update(['status' => PermitLetterGeneration::FAILED, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
