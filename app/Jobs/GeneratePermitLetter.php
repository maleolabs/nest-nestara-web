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
use Illuminate\Support\Facades\DB;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\BackOfficeStatusEnum;

class GeneratePermitLetter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;

    public function __construct(public int $generationId) {}

    public function handle(PerizinanService $perizinan, EncryptionService $encryption, JenisPerizinanService $jenis, EntriDataService $entri, SuratService $surat): void
    {
        $generation = DB::transaction(function () {
            $generation = PermitLetterGeneration::whereKey($this->generationId)->lockForUpdate()->firstOrFail();
            if ($generation->status === PermitLetterGeneration::COMPLETED || $generation->status === PermitLetterGeneration::FAILED) return null;
            if ($generation->status === PermitLetterGeneration::PROCESSING && $generation->processing_at?->gt(now()->subMinutes(15))) return null;
            $generation->update(['status' => PermitLetterGeneration::PROCESSING, 'processing_at' => now(), 'attempts' => $generation->attempts + 1, 'error' => null]);
            return $generation->fresh();
        });
        if (!$generation) return;
        try {
            $izin = $perizinan->find($generation->izin_id);
            if (!$izin) throw new \RuntimeException('Izin not found');
            $latest = $izin->latestStatus()->first();
            if (!$latest || $latest->nama !== StatusIzinEnum::PROSES || $latest->keterangan !== BackOfficeStatusEnum::DIIZINKAN) {
                throw new \RuntimeException('Permit is not eligible for letter generation');
            }
            $view = $jenis->getPdfTemplate($izin->jenis_perizinan_id);
            $data = $entri->getDataIzinGroupByNama($izin->id, $izin->jenis_perizinan_id);
            if (!$view || count($data) === 0) throw new \RuntimeException('Permit data incomplete');
            $id = $encryption->encryptId($izin->id);
            $pdf = Pdf::loadView($view, ['title' => "IZIN-{$izin->no_pendaftaran}", 'izin' => $izin, 'qrcode' => generate_qrcode_as_uri(route('confirm.izin', ['izin_id' => $id]), 130), 'kop_surat' => $surat->getKopSurat(), 'data_izin' => $data, 'konten' => $surat->getSuratContentByJenis($izin->jenis_perizinan_id)]);
            $path = "permit-letters/{$izin->id}/{$generation->id}.pdf";
            if (!Storage::disk('local')->put($path, $pdf->output()) || !Storage::disk('local')->exists($path)) throw new \RuntimeException('Permit letter could not be stored');
            $generation->update(['status' => PermitLetterGeneration::COMPLETED, 'path' => $path]);
            $perizinan->updateCetakIzinStatus($izin->id, null);
        } catch (\Throwable $e) {
            $generation->update(['status' => $generation->attempts >= $this->tries ? PermitLetterGeneration::FAILED : PermitLetterGeneration::QUEUED, 'error' => $e->getMessage(), 'processing_at' => null]);
            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        PermitLetterGeneration::whereKey($this->generationId)
            ->whereIn('status', [PermitLetterGeneration::QUEUED, PermitLetterGeneration::PROCESSING])
            ->update(['status' => PermitLetterGeneration::FAILED, 'processing_at' => null, 'error' => $e->getMessage()]);
    }
}
