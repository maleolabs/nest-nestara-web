<?php

namespace Tests\Unit\Support;

use App\Livewire\Dashboard\BackOffice\Partials\EntriDataTrait;
use App\Services\DomainServices\OcrService;
use Mockery;
use PHPUnit\Framework\TestCase;

class EntriDataOcrPrefillTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_ocr_does_not_overwrite_manual_value(): void
    {
        $ocr = Mockery::mock(OcrService::class);
        $ocr->shouldReceive('getEntriDataByIzinId')->once()->andReturn([
            'nama' => 'OCR name',
            'alamat' => 'OCR address',
        ]);

        $component = new class($ocr) {
            use EntriDataTrait;

            public function __construct(private OcrService $ocr) {}

            protected function getOcrService(): OcrService
            {
                return $this->ocr;
            }
        };
        $component->preview_izin_id = 1;
        $component->entri_data = [
            (object) ['id' => 1, 'nama' => 'nama'],
            (object) ['id' => 2, 'nama' => 'alamat'],
        ];
        $component->entri_data_izin = [1 => 'Manual name'];

        $component->automaticFill();

        self::assertSame('Manual name', $component->entri_data_izin[1]);
        self::assertSame('OCR address', $component->entri_data_izin[2]);
    }
}
