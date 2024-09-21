<?php

namespace Tests\Feature\APIClient;

use App\Services\APIClient\OcrAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OcrAPIServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $apiService = app(OcrAPIService::class);

        $data = $apiService->getEntriDataValueByIzinId(1);
        dd($data);
    }
}
