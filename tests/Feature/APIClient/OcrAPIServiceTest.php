<?php

namespace Tests\Feature\APIClient;

use App\Services\APIClient\dto\OcrRequest;
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

        $data = $apiService->getEntriDataValueByIzinId(5);
        // dd($data);
    }

    public function test_send_ocr_request(): void
    {
        $apiService = app(OcrAPIService::class);

        $request = OcrRequest::builder()
                                ->setIzinId(10)
                                ->setJenisPerizinanId(167)
                                ->setSyaratIzinId(855)
                                ->setImageUrl("http://sistem-informasi-dinas-pmptsp.me/api/files/MTcyNTQzMzA1MDg1NS10ZXN0LmpwZw==")
                                ->build();

        $response = $apiService->sendOcrRequest($request);
        dd($response->toArray());
    }

}