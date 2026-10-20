<?php

namespace Tests\Feature\DomainService;

use App\Models\DokumenPemohon;
use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\OcrRequest;
use App\Models\Pemohon;
use App\Models\SyaratIzin;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\OcrService;
use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * Idempotency OCR pipeline (plan 11 P1.5): klaim baris ocr_requests,
 * skip syarat done/processing saat job dirun ulang (worker retry --tries=3),
 * pengiriman ke vision tetap via kontrak OcrAPIService (tidak diubah).
 *
 * requires DB dengan migration terpasang (RefreshDatabase). Parent rows
 * dibuat asli (jenis_perizinan → pemohon → izin, plus syarat_izin) karena
 * ocr_requests.izin_id = FK izin; klaim/idempotency diuji terhadap FK nyata.
 */
class OcrRequestIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    private Izin $izin;

    private SyaratIzin $syarat;

    private Pemohon $pemohon;

    protected function setUp(): void
    {
        parent::setUp();

        // rantai FK asli: izin → jenis_perizinan & pemohon
        $this->syarat = SyaratIzin::create(['nama' => 'foto ktp', 'tipe' => 'img']);
        $jenis = JenisPerizinan::create(['nama' => 'perizinan uji']);
        $this->pemohon = Pemohon::create([
            'sumber_identitas' => 'ktp',
            'nomor_id' => '99990001',
            'nama_pemohon' => 'pemohon uji',
            'no_telepon' => '081000000001',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan uji',
        ]);
        $this->izin = Izin::create([
            'jenis_perizinan_id' => $jenis->id,
            'pemohon_id' => $this->pemohon->id,
            'no_pendaftaran' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Swap port domain dengan mock; sendOcrRequest dibatasi $send_times,
     * checkOcrRequestStatus selalu selesai DONE.
     */
    private function swapPorts(int $send_times): void
    {
        $syaratService = Mockery::mock(SyaratIzinService::class);
        $syaratService->shouldReceive('getByTypeAndJenisPerizinan')->andReturn([$this->syarat]);
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
        $this->app->make(OcrService::class)->doOcrRequestForNewDataRegistation($this->izin);
    }

    public function test_first_run_claims_row_sends_once_and_marks_done(): void
    {
        $this->swapPorts(1);

        $this->runOcr();

        $row = $this->tripleRow();
        $this->assertNotNull($row, 'baris ocr_requests harus dipersist sebelum/di awal pengiriman');
        $this->assertSame(OcrRequest::STATUS_DONE, $row->status);
        $this->assertSame('req-1', $row->request_id);
        $this->assertSame($this->pemohon->id, (int) $row->pemohon_id);
    }

    public function test_retry_run_does_not_resend(): void
    {
        $this->swapPorts(1); // hanya 1 pengiriman, walau doOcr dijalankan 2x

        $this->runOcr();
        $this->runOcr(); // simulasi retry worker: done/processing tetap skip

        $this->assertSame(
            1,
            OcrRequest::where('izin_id', $this->izin->id)
                ->where('syarat_izin_id', $this->syarat->id)
                ->count(),
            'tidak boleh ada baris duplikat untuk triple idempotency'
        );
    }

    public function test_persist_first_pending_row_is_claimed_not_duplicated(): void
    {
        // simulasi persist-first di PerizinanServiceImpl@requestOcrForNewRegistration
        OcrRequest::create([
            'izin_id' => $this->izin->id,
            'syarat_izin_id' => $this->syarat->id,
            'pemohon_id' => $this->pemohon->id,
            'status' => OcrRequest::STATUS_PENDING,
        ]);
        $this->swapPorts(1);

        $this->runOcr();

        $this->assertSame(
            1,
            OcrRequest::where('izin_id', $this->izin->id)
                ->where('syarat_izin_id', $this->syarat->id)
                ->count()
        );
        $this->assertSame(OcrRequest::STATUS_DONE, $this->tripleRow()->status);
    }

    private function tripleRow(): ?OcrRequest
    {
        return OcrRequest::where('izin_id', $this->izin->id)
            ->where('syarat_izin_id', $this->syarat->id)
            ->first();
    }
}
