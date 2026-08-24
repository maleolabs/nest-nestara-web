<?php

namespace App\Services\APIClient\Impl;

use App\Exceptions\InvalidTokenException;
use App\Models\OcrRequest as ModelsOcrRequest;
use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\OcrAPIService;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Active OCR API implementation.
 *
 * Runtime DB config via get_pengaturan(OCR_API_KEY / OCR_BASE_URL), header X-API-KEY,
 * uses Illuminate\Support\Facades\Http (multipart). Bound as OcrAPIService in AppServiceProvider.
 */
class OcrAPIServiceImpl2 implements OcrAPIService
{

    private const AUTHORIZATION_HEADER = 'X-API-KEY';
    private const ACCEPT_HEADER = 'Accept';
    private const CONTENT_TYPE_HEADER = 'Content-Type';
    private const APPLICATION_JSON = 'application/json';
    private const MULTIPART_FORM_DATA = 'multipart/form-data';

    private const CACHE_TTL = 14400; // 4 hours

    private const REQUEST_TIMEOUT_SECONDS = 25;
    private const CONNECT_TIMEOUT_SECONDS = 5;
    private const FETCH_TIMEOUT_SECONDS = 5;

    public function getEntriDataValueByIzinId(int $izin_id): ?OcrResponse
    {
        try {
            $ocrRequest = ModelsOcrRequest::where('izin_id', $izin_id)->orderByDesc('created_at')->first();
            if (!$ocrRequest) {
                return null;
            }

            $endpoint = "/ocr/get-result/{$ocrRequest->request_id}";

            return $this->fetchAndParseResponse($endpoint);
        } catch (Exception $e) {
            Log::channel('app')->error('failed to get ocr results by izin id', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function sendOcrRequest(OcrRequest $request, ?string $token = null): ?OcrResponse
    {
        try {
            $endpoint = "/ocr/do-ocr";

            $response = $this->postAndParseResponse($endpoint, $request);

            if ($response) {
                ModelsOcrRequest::create([
                    'izin_id' => $request->getIzinId(),
                    'request_id' => $response->getData()['requestId']
                ]);
            }

            return $response;
        } catch (Exception $e) {
            Log::channel('app')->error('failed to send ocr request', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function checkOcrRequestStatus(int|string $request_id): ?OcrResponse
    {
        try {
            $endpoint = "/ocr/check-status/{$request_id}";

            return $this->fetchAndParseResponse($endpoint, [], false, self::FETCH_TIMEOUT_SECONDS);
        } catch (Exception $e) {
            Log::channel('app')->error('failed to check ocr request status', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function sendKtpImageRequest(string $image_contents, ?string $original_file_name, array $required_keys = []): ?OcrResponse
    {
        try {
            $endpoint = "/ocr/do-ocr";
            $uri = $this->getBaseUrl() . $endpoint;
            $headers = $this->getRequiredHeader();

            $multipart = [
                [
                    'name' => 'image',
                    'contents' => $image_contents,
                    'filename' => $original_file_name ?: 'ktp.jpg'
                ]
            ];
            foreach ($required_keys as $key) {
                $multipart[] = [
                    'name' => 'requiredKeys[]',
                    'contents' => $key
                ];
            }

            $response = Http::withHeaders($headers)
                ->timeout(self::REQUEST_TIMEOUT_SECONDS)
                ->connectTimeout(self::CONNECT_TIMEOUT_SECONDS)
                ->asMultipart()
                ->post($uri, $multipart);

            return $this->parseJsonResponse($response->body());
        } catch (ConnectionException $ce) {
            Log::channel('app')->error('failed to send ktp ocr request, got connection exception', ['error' => $ce->getMessage()]);
            return null;
        } catch (Exception $e) {
            Log::channel('app')->error('failed to send ktp ocr request', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function getOcrResultByRequestId(int|string $request_id): ?OcrResponse
    {
        try {
            $endpoint = "/ocr/get-result/{$request_id}";

            return $this->fetchAndParseResponse($endpoint, [], true, self::FETCH_TIMEOUT_SECONDS);
        } catch (Exception $e) {
            Log::channel('app')->error('failed to get ocr result by request id', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function postAndParseResponse(string $endpoint, OcrRequest $request, array $additional_header = [])
    {
        $uri = $this->getBaseUrl() . $endpoint;
        $headers = array_merge($this->getRequiredHeader(), $additional_header);

        try {
            $response = Http::withHeaders($headers)
                ->attach('imageUrl', $request->getImageUrl())
                ->asMultipart()
                ->post($uri, $request->getRequiredKeys());

            return $this->parseJsonResponse($response->body());
        } catch (RequestException $re) {
            return $this->handleRequestException($re);
        } catch (ConnectionException $ce) {
            Log::channel('app')->error('failed to post request, got connection exception', ['error' => $ce->getMessage()]);
            return null;
        }
    }

    private function fetchAndParseResponse(string $endpoint, array $additional_header = [], bool $cache = true, ?int $timeout_seconds = null)
    {
        $cacheKey = str_replace('/', '-', $endpoint);

        if ($cache) {
            $dataFromCache = $this->getCachedData($cacheKey);
            if ($dataFromCache) {
                return $this->parseJsonResponse($dataFromCache);
            }
        }

        $uri = $this->getBaseUrl(). $endpoint;
        $headers = array_merge($this->getRequiredHeader(), $additional_header);

        try {
            $http = Http::withHeaders($headers);
            if ($timeout_seconds !== null) {
                $http = $http->timeout($timeout_seconds)->connectTimeout(self::CONNECT_TIMEOUT_SECONDS);
            }

            $response = $http->get($uri);
            $responseData = $response->body();

            $ocrResponse = $this->parseJsonResponse($responseData);

            // guard: body non-JSON (mis. 404 kosong) menghasilkan OcrResponse null
            if ($cache && $ocrResponse !== null && ($ocrResponse->getData()['status'] ?? null) == 'DONE') {
                $this->saveCacheData($cacheKey, $responseData);
            }

            return $ocrResponse;
        } catch (RequestException $re) {
            return $this->handleRequestException($re);
        } catch (ConnectionException $ce) {
            Log::channel('app')->error('failed to fetch data from endpoint', ['error' => $ce->getMessage()]);
            return null;
        }
    }

    private function handleRequestException(RequestException $re): ?OcrResponse
    {
        $response = $re->getResponse();
        $statusCode = $response->getStatusCode();

        if ($statusCode === 400) {
            return $this->parseJsonResponse($response->getBody()->getContents());
        } else if ($statusCode === 401) {
            throw new InvalidTokenException('Unauthorized', 401);
        } else {
            Log::channel('app')->error('request exception with unknown error', ['error' => $re->getMessage()]);
        }

        return null;
    }

    private function getCachedData(string $cacheKey)
    {
        return Cache::get($cacheKey);
    }

    private function saveCacheData($key, $data)
    {
        Cache::remember($key, self::CACHE_TTL, function() use ($data) {
            return $data;
        });
    }

    private function parseJsonResponse($json): ?OcrResponse
    {
        if (is_string($json)) {
            $json = json_decode($json);
        } else if (is_array($json)) {
            $json = (object) $json;
        }
        return OcrResponse::fromJson($json);
    }

    private function getApiKey(): string
    {
        $api_key = get_pengaturan(OCR_API_KEY);
        if (!$api_key) {
            throw new Exception('api key for ocr service is not set');
        }
        return $api_key;
    }

    private function getBaseUrl(): string
    {
        $base_url = get_pengaturan(OCR_BASE_URL);
        if (!$base_url) {
            throw new Exception('base url for ocr service is not set');
        }
        return $base_url;
    }

    private function getRequiredHeader(): array
    {
        return [
            self::AUTHORIZATION_HEADER => $this->getApiKey(),
            self::ACCEPT_HEADER => self::APPLICATION_JSON
        ];
    }

}