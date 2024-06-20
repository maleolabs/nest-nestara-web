<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\RegionAPIService;
use App\Services\AppService\FileService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class EmsifaAPIService implements RegionAPIService
{

    private const BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api/';

    private const GET_METHOD = 'GET';

    private const LOCAL_REGION_PATH = 'region';

    private function fileService(): FileService
    {
        return app(FileService::class);
    }
    
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
        $url = 'regencies/' . $province_id . '.json';
        return $this->fetchData(self::GET_METHOD, $url);
    }

    public function getRegency(int $regency_id): string
    {
        $url = 'regency/' . $regency_id . '.json';
        $province_data = $this->fetchData(self::GET_METHOD, $url);
        return $province_data['name'];
    }

    public function getDistrictsByRegency(int $regency_id): array
    {
        $url = 'districts/' . $regency_id . '.json';
        return $this->fetchData(self::GET_METHOD, $url);
    }

    public function getDistrict(int $district_id): string
    {
        $url = 'district/' . $district_id . '.json';
        $province_data = $this->fetchData(self::GET_METHOD, $url);
        return $province_data['name'];
    }

    public function getVillagesByDistrict(int $district_id): array
    {
        $url = 'villages/' . $district_id . '.json';
        return $this->fetchData(self::GET_METHOD, $url);
    }

    public function getVillage(int $village_id): string
    {
        $url = 'village/' . $village_id . '.json';
        $province_data = $this->fetchData(self::GET_METHOD, $url);
        return $province_data['name'];
    }

    private function fetchData(string $method, string $param): array
    {
        $client = new Client();
        $path = self::LOCAL_REGION_PATH . '/' . $param;

        $data = [];

        try {
            $response = $client->request($method, self::BASE_URL . $param);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                $this->fileService()->save($path, $response->getBody());
                return $data;
            }
        } catch (ConnectException $ce) {
            $data = $this->fileService()->getData($path);
            return $data;
        } catch (RequestException $re) {
            $data = $this->fileService()->getData($path);
            return $data;
        } 
        
        return $data;
    }

}