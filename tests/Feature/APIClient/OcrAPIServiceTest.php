<?php

namespace Tests\Feature\APIClient;

use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\OcrAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class OcrAPIServiceTest extends TestCase
{

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
        
        assertNotNull($response);
        assertNotNull($response->getData());
        assertTrue($response->isSuccess());
        assertNull($response->getErrorMessage());

        $requestStatus = $response->getData()['status'];
        assertNotNull($requestStatus);

        $requestId = $response->getData()['requestId'];
        assertNotNull($requestId);
        
        do {
            $response = $apiService->checkOcrRequestStatus($requestId);
            assertNotNull($response);
            assertNotNull($response->getData());
            assertTrue($response->isSuccess());
            assertNull($response->getErrorMessage());
            
            $requestStatus = $response->getData()['status'];

            sleep(5);
        } while ($requestStatus == 'WAITING' || $requestStatus == 'PROCESSING');

        assertSame('DONE', $requestStatus);
        assertNotNull($response->getData()['duration']);
    }

}