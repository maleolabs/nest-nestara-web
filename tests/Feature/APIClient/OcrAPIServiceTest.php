<?php

namespace Tests\Feature\APIClient;

use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\EntriDataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use App\Services\APIClient\Impl\OcrAPIServiceImpl2;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class OcrAPIServiceTest extends TestCase
{

    public function test_send_ocr_request(): void
    {
        Http::fake([
            '*' => Http::response(['success' => true, 'data' => ['status' => 'DONE', 'requestId' => 42, 'duration' => 1]], 200),
        ]);
        set_pengaturan(OCR_BASE_URL, 'http://ocr.test', OCR_API_SETT_GROUP);
        set_pengaturan(OCR_API_KEY, 'test-key', OCR_API_SETT_GROUP);
        $entriDataService = app(EntriDataService::class);
        $apiService = app(OcrAPIService::class);

        $requiredKeys = array_column($entriDataService->getByJenisPerizinanId(167), 'nama');

        $request = OcrRequest::builder()
                                ->setIzinId(10)
                                ->setJenisPerizinanId(167)
                                ->setSyaratIzinId(855)
                                ->setImageUrl("http://localhost:8000/api/files/MTc0MTQxMzg4Nzg1NS0xNzI2NTE2ODIxODU0LWFiZDU5NzU2LTRlZmUtNDQ4OS04ZmEyLWRhOGJjZDU2NGJiYS5qcGVn")
                                ->setRequiredKeys($requiredKeys)
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

        $checkCount = 0;
        
        do {
            if ($checkCount > 10) {
                break;
            }

            $response = $apiService->checkOcrRequestStatus($requestId);
            assertNotNull($response);
            assertNotNull($response->getData());
            assertTrue($response->isSuccess());
            assertNull($response->getErrorMessage());
            
            $requestStatus = $response->getData()['status'];

            $checkCount++;

            // external OCR is faked; no polling delay needed
        } while ($requestStatus == 'WAITING' || $requestStatus == 'PROCESSING');

        assertSame('DONE', $requestStatus);
        assertNotNull($response->getData()['duration']);
    }

}
