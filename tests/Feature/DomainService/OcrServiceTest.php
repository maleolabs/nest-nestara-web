<?php

namespace Tests\Feature\DomainService;

use App\Services\DomainServices\OcrService;
use App\Services\DomainServices\PerizinanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;

class OcrServiceTest extends TestCase
{
    
    public function test_do_ocr(): void
    {
        $izinService = app(PerizinanService::class);
        $ocrService = app(OcrService::class);

        $izin = $izinService->find(1);
        assertNotNull($izin);

        $ocrService->doOcrRequestForNewDataRegistation($izin);       
    }
    
}