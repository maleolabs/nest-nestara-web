<?php

namespace Tests\Feature\DomainService;

use App\Models\DokumenPemohon;
use App\Models\Izin;
use App\Models\OcrRequest;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * Idempotency OCR pipeline (plan 11 P1.5): klaim baris ocr_requests,
 * skip syarat done/processing saat job dirun ulang (worker retry --tries=3),
 * pengiriman ke vision tetap via kontrak OcrAPIService (tidak diubah).
 *
 * requires DB (MySQL) — MySQL tidak tersedia di sandbox; test ini hanya
 * berjalan di env dengan DB yang sudah di-migrate, termasuk migration
 * 2026_10_20_000001_make_ocr_requests_idempotent.
 */
class OcrRequestIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function fakeIzin(): Izin
    {
        return (new Izin)->forceFill([
            'id' => 101,
            'pemohon_id' => 7,
            'jenis_perizinan_id' => 5,
        ]);
    }

    /**
     * Swap port domain dengan mock; sendOcrRequest dibatasi $send_times,
     * checkOcrRequestStatus selalu selesai DONE.
     */
    private function swapPorts(int $send_times): void
    {
        $syarat = (object) ['id' => 55];

        $syaratService = Mockery::mock(SyaratIzinService::class);
        $syaratService->shouldReceive('getByTypeAndJenisPerizinan')->andReturn([$syarat]);
        $this->app->instance(SyaratIzinService::class, $syaratService);

        $dokumenService = Mockery::mock(DokumenPemohonService::class);
        $dokumenService->shouldReceive('findByPemohonAndSyaratAndIzin')
            ->andReturn((new DokumenPemohon)->forceFill(['nama_file' => 'temp-x.pdf']));
        $this->app->instance(DokumenPemohonService::class, $dokumenService);

        $entriService = Mockery::mock(EntriDataService::class);
        $entriService->shouldReceive('getByJenisPerizinanId')->andReturn([]);
        $this->app->instance(EntriDataService::class, $entriService);

        $ocrAPI = Mockery::mock(OcrAPIService::class);
        $ocrAPI->shouldReceive('sendOcrRequest')->times($send_times)
            ->andReturn(new OcrResponse(['requestId' => 'req-1', 'status' => 'WAITING'], true, null));
        $ocrAPI->shouldReceive('checkOcrRequestStatus')->times($send_times)
            ->andReturn(new OcrResponse(['status' => 'DONE', 'duration' => 1.2], true, null));
        $this->app->instance(OcrAPIService::class, $ocrAPI);
    }

    private function runOcr(): void
    {
        $this->app->make(OcrService::class)->doOcrRequestForNewDataRegistation($this->fakeIzin());
    }

    public function test_first_run_claims_row_sends_once_and_marks_done(): void
    {
        $this->swapPorts(1);

        $this->runOcr();

        $row = OcrRequest::where('izin_id', 101)->where('syarat_izin_id', 55)->first();
        $this->assertNotNull($row, 'baris ocr_requests harus dipersist sebelum/di awal pengiriman');
        $this->assertSame(OcrRequest::STATUS_DONE, $row->status);
        $this->assertSame('req-1', $row->request_id);
        $this->assertSame(7, (int) $row->pemohon_id);
    }

    public function test_retry_run_does_not_resend(): void
    {
        $this->swapPorts(1); // hanya 1 pengiriman, walau doOcr dijalankan 2x

        $this->runOcr();
        $this->runOcr(); // simulasi retry worker: belum done→processing pun tetap skip

        $this->assertSame(
            1,
            OcrRequest::where('izin_id', 101)->where('syarat_izin_id', 55)->count(),
            'tidak boleh ada baris duplikat untuk triple idempotency'
        );
    }

    public function test_persist_first_pending_row_is_claimed_not_duplicated(): void
    {
        // simulasi persist-first di PerizinanServiceImpl@requestOcrForNewRegistration
        OcrRequest::create([
            'izin_id' => 101,
            'syarat_izin_id' => 55,
            'pemohon_id' => 7,
            'status' => OcrRequest::STATUS_PENDING,
        ]);
        $this->swapPorts(1);

        $this->runOcr();

        $this->assertSame(
            1,
            OcrRequest::where('izin_id', 101)->where('syarat_izin_id', 55)->count()
        );
        $this->assertSame(OcrRequest::STATUS_DONE, OcrRequest::first()->status);
    }
}
