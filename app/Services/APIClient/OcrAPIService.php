<?php

namespace App\Services\APIClient;

interface OcrAPIService
{

    /**
     * Mengambil entri data value berdasarkan ID dari izin tertentu
     * 
     * @param int $izin_id ID dari izin
     * @return array Value dari entri data
     */
    function getEntriDataValueByIzinId(int $izin_id): array;

}