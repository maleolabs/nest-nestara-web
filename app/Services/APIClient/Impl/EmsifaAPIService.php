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

    /** in-memory memo — hindari disk-read + json_decode berulang dalam 1 request */
    private static array $memo = [];

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
        $province_data = $this->fetchData(self::GET_METHOD, "province/{$province_id}.json");
        return count($province_data) > 0 ? $province_data['name'] : 'provinsi ' . self::NOT_FOUND;
    }

    public function getRegenciesByProvince(int $province_id): array
    {
        return $this->fetchData(self::GET_METHOD, "regencies/{$province_id}.json");
    }

    public function getRegency(int $regency_id): string
    {
        $regency_data = $this->fetchData(self::GET_METHOD, "regency/{$regency_id}.json");
        return count($regency_data) > 0 ? $regency_data['name'] : 'kabupaten ' . self::NOT_FOUND;
    }

    public function getDistrictsByRegency(int $regency_id): array
    {
        return $this->fetchData(self::GET_METHOD, "districts/{$regency_id}.json");
    }

    public function getDistrict(int $district_id): string
    {
        $district_data = $this->fetchData(self::GET_METHOD, "district/{$district_id}.json");
        return count($district_data) > 0 ? $district_data['name'] : 'kecamatan ' . self::NOT_FOUND;
    }

    public function getVillagesByDistrict(int $district_id): array
    {
        return $this->fetchData(self::GET_METHOD, "villages/{$district_id}.json");
    }

    public function getVillage(int $village_id): string
    {
        $village_data = $this->fetchData(self::GET_METHOD, "village/{$village_id}.json");
        return count($village_data) > 0 ? $village_data['name'] : 'kelurahan ' . self::NOT_FOUND;
    }

    private function fetchData(string $method, string $param): array
    {
        // 1) in-memory memo (per request) — 1x baca untuk N baris
        if (array_key_exists($param, self::$memo)) {
            return self::$memo[$param];
        }

        // 2) application cache — termasuk NEGATIVE result (kode tidak ada),
        //    supaya kode invalid tidak menembak HTTP berulang tiap render
        $cacheKey = 'region:' . md5($param);
        $cached = cache()->get($cacheKey);
        if (is_array($cached)) {
            return self::$memo[$param] = $cached;
        }

        $client = new Client([
            'timeout' => 3,
            'connect_timeout' => 2,
        ]);
        $path = self::LOCAL_REGION_PATH . '/' . $param;

        $data = [];

        try {
            $data = $this->fileService()->getData($path);
            if (count($data) > 0) {
                return self::$memo[$param] = cache()->remember($cacheKey, now()->addDay(), fn () => $data);
            }
        } catch (Exception $e) {}

        if (!$this->get_all_province && str_starts_with($param, 'province')) {
            return [];
        }
        try {
            $response = $client->request($method, $this->base_url . $param);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                $this->fileService()->save($path, $response->getBody());
                return self::$memo[$param] = cache()->remember($cacheKey, now()->addDay(), fn () => $data);
            }
        } catch (ConnectException $ce) {
            $data = $this->fileService()->getData($path);
            // jangan cache hasil kosong terlalu lama saat koneksi gagal
            return self::$memo[$param] = $data;
        } catch (RequestException $re) {
            // 404 / invalid kode — cache singkat agar tidak retry tiap render
            return self::$memo[$param] = cache()->remember($cacheKey, now()->addMinutes(10), fn () => []);
        }

        return self::$memo[$param] = $data;
    }

}