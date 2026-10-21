<?php

namespace App\Services\APIClient\Impl;

use App\Exceptions\InvalidTokenException;
use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\OcrAPIService;
use App\Services\Security\TokenService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * @deprecated Use OcrAPIServiceImpl2 (DB config X-API-KEY). Kept for reference, will be removed v2. Binding in AppServiceProvider uses Impl2
 *
 * Legacy OCR implementation using Guzzle Client + TokenService + config('api-client.ocr_api.base_url').
 * No longer bound. Retained only for reference / branch compatibility.
 */
class OcrAPIServiceImpl implements OcrAPIService
{

    private $base_url;

    private Client $client;
    private TokenService $tokenService;

    private const AUTHORIZATION_HEADER = 'X-API-KEY';
    private const CONTENT_TYPE_HEADER = 'Content-Type';
    private const APPLICATION_JSON = 'application/json';

    private const CACHE_TTL = 7200; // 6 hours

    public function __construct(Client $client, TokenService $tokenService)
    {
        $this->client = $client;
        $this->tokenService = $tokenService;
        $this->base_url = config('api-client.ocr_api.base_url');
    }

    public function getEntriDataValueByIzinId(int $izin_id): ?OcrResponse
    {
        $path = "ocr/find/$izin_id";
        return $this->fetchDataAndParseResponse($path, false);
    }

    public function sendOcrRequest(OcrRequest $request, ?string $token = null): ?OcrResponse
    {
        $path = 'ocr/do-ocr';
        return $this->postDataAndParseResponse($path, $request, $token);
    }

    public function checkOcrRequestStatus(int|string $request_id): ?OcrResponse
    {
        $path = 'ocr/check-status/' . $request_id;
        return $this->fetchDataAndParseResponse($path, false);
    }

    private function postDataAndParseResponse($path, $request, $token = null)
    {
        $uri = $this->base_url . $path;
        $headers = $this->getDefaultHeaders($token);

        try {
            $response = $this->client->post($uri, [
                'headers' => $headers,
                'json' => $request->toArray()
            ]);
            return $this->parseJsonResponse($response->getBody()->getContents());
        } catch (RequestException $re) {
            return $this->handleRequestException($re);
        } catch (ConnectionException $ce) {
            Log::channel('app')->error($ce->getMessage());
            return null;
        }
    }

    private function fetchDataAndParseResponse($path, $cache = true)
    {
        $uri = $this->base_url . $path;
        $headers = $this->getDefaultHeaders();
        $key = str_replace('/', '-', $path);

        $dataFromCache = $this->getCacheData($key);
        if ($dataFromCache && $cache) {
            return $this->parseJsonResponse($dataFromCache);
        }

        try {
            $response = $this->client->get($uri, [
                'headers' => $headers
            ]);
            $contents = $response->getBody()->getContents();
            
            if ($cache) {
                $this->saveCacheData($key, $contents);
            }
            
            return $this->parseJsonResponse($contents);
        } catch (RequestException $re) {
            return $this->handleRequestException($re);
        } catch (ConnectionException $ce) {
            Log::channel('app')->info($ce->getMessage());
            return null;
        }
    }

    private function saveCacheData($key, $data)
    {
        Cache::remember($key, self::CACHE_TTL, function() use ($data) {
            return $data;
        });
    }

    private function getCacheData($key)
    {
        return Cache::get($key);
    }

    private function getDefaultHeaders(): array
    {
        return [
            self::AUTHORIZATION_HEADER => $this->getApiToken(),
            self::CONTENT_TYPE_HEADER => self::APPLICATION_JSON
        ];
    }

    private function parseJsonResponse(string $body): ?OcrResponse
    {
        $json = json_decode($body);
        return OcrResponse::fromJson($json);
    }

    private function handleRequestException(RequestException $re): ?OcrResponse
    {
        $response = $re->getResponse();
        $statusCode = $response->getStatusCode();

        if ($statusCode === 400) {
            return $this->parseJsonResponse($response->getBody()->getContents());
        } else if ($statusCode === 401) {
            $this->handleInvalidToken();
        }

        return null;
    }

    private function handleInvalidToken()
    {
        throw new InvalidTokenException("unauthorized");
    }

    private function getApiToken($request_token = null): string
    {
        $token = $request_token ?? $this->tokenService->getToken();
        if (!$token) {
            $this->handleInvalidToken();
        }
        return $token;
    }

}
