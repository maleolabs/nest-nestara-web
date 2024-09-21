<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\OcrAPIService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class OcrAPIServiceImpl implements OcrAPIService
{

    private $base_url;

    public function __construct()
    {
        $this->base_url = config('api-client.ocr_api.base_url');
    }

    public function getEntriDataValueByIzinId(int $izin_id): array
    {
        $path = "ocr/find/$izin_id";
        return $this->fetchData($path);
    }

    private function fetchData($path)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', $this->base_url . $path);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                return $data;
            }
        } catch (ConnectionException | RequestException $e) {
            Log::info($e->getMessage());
        }
        return [];
    }

}