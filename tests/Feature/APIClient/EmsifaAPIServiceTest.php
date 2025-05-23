<?php

namespace Tests\Feature\APIClient;

use App\Services\APIClient\RegionAPIService;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

class EmsifaAPIServiceTest extends TestCase
{

    public function test_get_provinces(): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
        $dataFromAPI = json_decode($response->getBody(), true);

        $regionAPIService = app(RegionAPIService::class);
        $dataFromService = $regionAPIService->getProvinces();

        assertEquals($dataFromAPI, $dataFromService);
    }

    public function test_get_province(): void
    {
        $regionAPIService = app(RegionAPIService::class);
        $gorontalo_id = 75;
        $gorontalo = $regionAPIService->getProvince($gorontalo_id);
        assertSame('GORONTALO', $gorontalo);
    }

    public function test_get_regencies(): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://emsifa.github.io/api-wilayah-indonesia/api/regencies/75.json');
        $dataFromAPI = json_decode($response->getBody(), true);

        $regionAPIService = app(RegionAPIService::class);
        $dataFromService = $regionAPIService->getRegenciesByProvince(75);

        assertEquals($dataFromAPI, $dataFromService);
    }

    public function test_get_regency(): void
    {
        $regionAPIService = app(RegionAPIService::class);
        $bonbol_id = 7504;
        $bonbol = $regionAPIService->getRegency($bonbol_id);
        assertSame('KABUPATEN BONE BOLANGO', $bonbol);
    }

    public function test_get_districts(): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://emsifa.github.io/api-wilayah-indonesia/api/districts/7504.json');
        $dataFromAPI = json_decode($response->getBody(), true);

        $regionAPIService = app(RegionAPIService::class);
        $dataFromService = $regionAPIService->getDistrictsByRegency(7504);

        assertEquals($dataFromAPI, $dataFromService);
    }

    public function test_get_district(): void
    {
        $regionAPIService = app(RegionAPIService::class);
        $suwawa_id = 7504030;
        $suwawa = $regionAPIService->getDistrict($suwawa_id);
        assertSame('SUWAWA', $suwawa);
    }

    public function test_get_villages(): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://emsifa.github.io/api-wilayah-indonesia/api/villages/7504030.json');
        $dataFromAPI = json_decode($response->getBody(), true);

        $regionAPIService = app(RegionAPIService::class);
        $dataFromService = $regionAPIService->getVillagesByDistrict(7504030);

        assertEquals($dataFromAPI, $dataFromService);
    }

    public function test_get_village(): void
    {
        $regionAPIService = app(RegionAPIService::class);
        $ulanta_id = 7504030023;
        $ulanta = $regionAPIService->getVillage($ulanta_id);
        assertSame('ULANTA', $ulanta);
    }

}