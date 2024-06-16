<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\RegionAPIService;
use GuzzleHttp\Client;

class EmsifaAPIService implements RegionAPIService
{

    private const BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api/';

    private const GET_METHOD = 'GET';
    
    public function getProvinces(): array
    {
        return $this->fetchData(self::GET_METHOD, 'provinces.json');
    }

    public function getProvince(int $province_id): string
    {
        $url = 'province/' . $province_id . '.json';
        $province_data = $this->fetchData(self::GET_METHOD, $url);
        return $province_data['name'];
    }

    public function getRegenciesByProvince(int $province_id): array
    {
        return [];
    }

    public function getRegency(int $regency_id): string
    {
        return '';
    }

    public function getDistricsByRegency(int $regency_id): array
    {
        return [];
    }

    public function getDistrict(int $discrict_id): string
    {
        return '';
    }

    public function getVillagesByDistrict(int $discrict_id): array
    {
        return [];
    }

    public function getVillage(int $village_id): string
    {
        return '';
    }

    private function fetchData(string $method, string $param): array
    {
        $client = new Client();
        $response = $client->request($method, self::BASE_URL . $param);
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            return $data;
        }
        return response()->json(
            ['error' => 'failed to fetch data'],
            $response->getStatusCode()
        );
    }

}