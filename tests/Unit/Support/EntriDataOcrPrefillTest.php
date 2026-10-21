<?php

namespace Tests\Unit\Support;

use App\Livewire\Dashboard\BackOffice\Partials\EntriDataTrait;
use App\Services\DomainServices\OcrService;
use Mockery;
use Tests\TestCase;

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
        self::assertSame('partial', $component->ocr_prefill_status);
        self::assertSame(1, $component->ocr_prefill_mapped_count);
        self::assertSame(1, $component->ocr_prefill_unmapped_count);
    }

    public function test_null_result_is_empty(): void
    {
        $ocr = Mockery::mock(OcrService::class);
        $ocr->shouldReceive('getEntriDataByIzinId')->andReturn(null);
        $component = new class($ocr) { use EntriDataTrait; public function __construct(private OcrService $ocr) {} protected function getOcrService(): OcrService { return $this->ocr; } };
        $component->preview_izin_id = 1; $component->entri_data = [];
        $component->automaticFill();
        self::assertSame('empty', $component->ocr_prefill_status);
    }

    public function test_exception_is_error(): void
    {
        $ocr = Mockery::mock(OcrService::class);
        $ocr->shouldReceive('getEntriDataByIzinId')->andThrow(new \RuntimeException('OCR down'));
        $component = new class($ocr) { use EntriDataTrait; public function __construct(private OcrService $ocr) {} protected function getOcrService(): OcrService { return $this->ocr; } };
        $component->preview_izin_id = 1; $component->entri_data = [];
        $component->automaticFill();
        self::assertSame('error', $component->ocr_prefill_status);
    }

    public function test_all_fields_mapped_is_success(): void
    {
        $ocr = Mockery::mock(OcrService::class);
        $ocr->shouldReceive('getEntriDataByIzinId')->andReturn(['nama' => '  OCR  ']);
        $component = new class($ocr) { use EntriDataTrait; public function __construct(private OcrService $ocr) {} protected function getOcrService(): OcrService { return $this->ocr; } };
        $component->preview_izin_id = 1; $component->entri_data = [(object) ['id' => 1, 'nama' => 'nama']]; $component->entri_data_izin = [];
        $component->automaticFill();
        self::assertSame('success', $component->ocr_prefill_status);
        self::assertSame('  OCR  ', $component->entri_data_izin[1]);
    }
}
