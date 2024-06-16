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
        dump($gorontalo);
        assertSame('GORONTALO', $gorontalo);
    }

    public function test_get_regencies(): void
    {

    }

    public function test_get_regency(): void
    {

    }

    public function test_get_districts(): void
    {

    }

    public function test_get_district(): void
    {

    }

    public function test_get_villages(): void
    {

    }

    public function test_get_village(): void
    {

    }

}