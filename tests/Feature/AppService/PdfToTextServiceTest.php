<?php

namespace Tests\Feature\AppService;

use App\Services\AppService\PdfToTextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PdfToTextServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $html_content = $this->pdfToTextService()->getText('1720206945854-proposal pmptsp.pdf');
    }

    private function pdfToTextService(): PdfToTextService
    {
        return app(PdfToTextService::class);
    }
}
