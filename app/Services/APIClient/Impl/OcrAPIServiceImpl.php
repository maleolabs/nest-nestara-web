<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\OcrAPIService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class OcrAPIServiceImpl implements OcrAPIService
{

    private $base_url;

    private Client $client;

    private const AUTHORIZATION_HEADER = 'Authorization';
    private const CONTENT_TYPE_HEADER = 'Content-Type';
    private const APPLICATION_JSON = 'application/json';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->base_url = config('api-client.ocr_api.base_url');
    }

    public function getEntriDataValueByIzinId(int $izin_id): ?OcrResponse
    {
        $path = "ocr/find/$izin_id";
        return $this->fetchDataAndParseResponse($path);
    }

    public function sendOcrRequest(OcrRequest $request): ?OcrResponse
    {
        $uri = $this->base_url . 'ocr/do-ocr';
        $headers = $this->getDefaultHeaders();

        try {
            $response = $this->client->post($uri, [
                'headers' => $headers,
                'json' => $request->toArray()
            ]);
            return $this->parseJsonResponse($response->getBody()->getContents());
        } catch (RequestException $re) {
            $this->handleRequestException($re);
        } catch (ConnectionException $ce) {
            Log::error($ce->getMessage());
            return null;
        }
    }

    private function fetchDataAndParseResponse($path)
    {
        $uri = $this->base_url . $path;
        $headers = $this->getDefaultHeaders();

        try {
            $response = $this->client->get($uri, [
                'headers' => $headers
            ]);
            return $this->parseJsonResponse($response->getBody()->getContents());
        } catch (RequestException $re) {
            $this->handleRequestException($re);
        } catch (ConnectionException $ce) {
            Log::info($ce->getMessage());
            return null;
        }
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
            // todo : get new token
        }
    }

    private function getApiToken(): string
    {
        return "";
    }

}