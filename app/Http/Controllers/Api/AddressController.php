<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\APIClient\RegionAPIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AddressController extends Controller
{
    private const TYPE_PROVINCE = 1;
    private const TYPE_REGENCY = 2;
    private const TYPE_DISTRICT = 3;
    private const TYPE_VILLAGE = 4;

    private RegionAPIService $regionService;

    public function __construct(RegionAPIService $regionService)
    {
        $this->regionService = $regionService;
    }

    /**
     * Method untuk mengecek tipe dan memanggil service yang sesuai.
     */
    private function handleByType(int $type, $parent_id = null, $id = null)
    {
        $handlers = [
            self::TYPE_PROVINCE => fn() => $id ? $this->regionService->getProvince($id) : $this->regionService->getProvinces(),
            self::TYPE_REGENCY => fn() => $id ? $this->regionService->getRegency($id) : $this->regionService->getRegenciesByProvince($parent_id),
            self::TYPE_DISTRICT => fn() => $id ? $this->regionService->getDistrict($id) : $this->regionService->getDistrictsByRegency($parent_id),
            self::TYPE_VILLAGE => fn() => $id ? $this->regionService->getVillage($id) : $this->regionService->getVillagesByDistrict($parent_id),
        ];

        return $handlers[$type] ?? null;
    }


    /**
     * Validasi tipe.
     */
    private function isValidType(int $type): bool
    {
        return in_array($type, [self::TYPE_PROVINCE, self::TYPE_REGENCY, self::TYPE_DISTRICT, self::TYPE_VILLAGE]);
    }

    /**
     * Mendapatkan region berdasarkan parent_id dan tipe.
     */
    public function getRegions($type, $parent_id): JsonResponse
    {
        $parent_id = intval($parent_id);

        if (!$this->isValidType($type)) {
            return response()->json(['error' => 'Invalid type'], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->handleByType($type, $parent_id);

        if (!$data) {
            return response()->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($data(), Response::HTTP_OK);
    }

    /**
     * Cari region berdasarkan ID dan tipe.
     */
    public function findById($type, int $id): JsonResponse
    {
        if (!$this->isValidType($type)) {
            return response()->json(['error' => 'Invalid type'], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->handleByType($type, null, $id);

        if (!$data) {
            return response()->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($data(), Response::HTTP_OK);
    }

    /**
     * Mencari region berdasarkan nama
     */
    public function findByName(string $name): JsonResponse
    {
        $name = strtolower($name);
        $all = $this->getAllRegions();
        foreach ($all as $region) {
            $regionName = strtolower($region['name']);
            if ($regionName == $name) {
                return response()->json($region, Response::HTTP_OK);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'region not found'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Mengambil semua region.
     */
    public function getAll(): JsonResponse
    {
        $data = $this->getAllRegions();
        return response()->json($data, Response::HTTP_OK);
    }

    private function getAllRegions(): array
    {
        $data = [];

        $provinces = $this->regionService->getProvinces();

        foreach ($provinces as $province) {
            $data[] = $province;

            $regencies = $this->regionService->getRegenciesByProvince($province['id']);
            foreach ($regencies as $regency) {
                $data[] = $regency;

                $districts = $this->regionService->getDistrictsByRegency($regency['id']);
                foreach ($districts as $district) {
                    $data[] = $district;

                    $villages = $this->regionService->getVillagesByDistrict($district['id']);
                    $data = array_merge($data, $villages);
                }
            }
        }

        return $data;
    }

}