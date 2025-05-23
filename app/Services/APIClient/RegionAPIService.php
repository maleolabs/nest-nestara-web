<?php

namespace App\Services\APIClient;

interface RegionAPIService
{
    
    function getProvinces(): array;

    function getProvince(int $province_id): string;

    function getRegenciesByProvince(int $province_id): array;

    function getRegency(int $regency_id): string;

    function getDistrictsByRegency(int $regency_id): array;

    function getDistrict(int $district_id): string;

    function getVillagesByDistrict(int $district_id): array;

    function getVillage(int $village_id): string;

}