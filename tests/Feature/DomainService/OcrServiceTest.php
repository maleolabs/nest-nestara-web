<?php

namespace Tests\Feature\DomainService;

use App\Services\DomainServices\OcrService;
use App\Services\DomainServices\PerizinanService;
use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pemohon;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;

class OcrServiceTest extends TestCase
{
    
    public function test_do_ocr(): void
    {
        $izinService = app(PerizinanService::class);
        $ocrService = app(OcrService::class);

        $jenisPerizinan = JenisPerizinan::create(['nama' => 'OCR test']);
        $pemohon = Pemohon::factory()->create();
        $izin = $izinService->save($jenisPerizinan->id, $pemohon->id);
        assertNotNull($izin);

        $res = $ocrService->doOcrRequestForNewDataRegistation($izin);       
    }
    
}
