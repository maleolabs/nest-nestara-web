<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\RegionAPIService;
use App\Services\AppService\FileService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class EmsifaAPIService implements RegionAPIService
{

    private $base_url;

    private const GET_METHOD = 'GET';

    private const LOCAL_REGION_PATH = 'region';

    private const NOT_FOUND = 'tidak ditemukan';

    private $get_all_province;

    public function __construct()
    {
        $this->base_url = config('api-client.region_api.base_url');
        $this->get_all_province = config('api-client.region_api.get_all_province');
    }

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
        return count($province_data) > 0 ? $province_data['name'] : 'provinsi ' . self::NOT_FOUND;
    }

    public function getRegenciesByProvince(int $province_id): array
    {
        $url = 'regencies/' . $province_id . '.json';
        return $this->fetchData(self::GET_METHOD, $url);
    }

    public function getRegency(int $regency_id): string
    {
        $url = 'regency/' . $regency_id . '.json';
        $regency_data = $this->fetchData(self::GET_METHOD, $url);
        return count($regency_data) > 0 ? $regency_data['name'] : 'kabupaten ' . self::NOT_FOUND;
    }

    public function getDistrictsByRegency(int $regency_id): array
    {
        $url = 'districts/' . $regency_id . '.json';
        return $this->fetchData(self::GET_METHOD, $url);
    }

    public function getDistrict(int $district_id): string
    {
        $url = 'district/' . $district_id . '.json';
        $district_data = $this->fetchData(self::GET_METHOD, $url);
        return count($district_data) > 0 ? $district_data['name'] : 'kecamatan ' . self::NOT_FOUND;
    }

    public function getVillagesByDistrict(int $district_id): array
    {
        $url = 'villages/' . $district_id . '.json';
        return $this->fetchData(self::GET_METHOD, $url);
    }

    public function getVillage(int $village_id): string
    {
        $url = 'village/' . $village_id . '.json';
        $village_data = $this->fetchData(self::GET_METHOD, $url);
        return count($village_data) > 0 ? $village_data['name'] : 'kelurahan ' . self::NOT_FOUND;
    }

    private function fetchData(string $method, string $param): array
    {
        $client = new Client();
        $path = self::LOCAL_REGION_PATH . '/' . $param;
        
        $data = [];

        try {
            $data = $this->fileService()->getData($path);
            if (count($data) > 0)
                return $data;
        } catch (Exception $e) {}
        
        if (!$this->get_all_province && str_starts_with($param, 'province')) {
            return [];
        }
        try {
            $response = $client->request($method, $this->base_url . $param);
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