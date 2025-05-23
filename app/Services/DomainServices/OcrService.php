<?php

namespace App\Services\DomainServices;

use App\Models\Izin;

interface OcrService
{

    /**
     * Melakukan request OCR untuk data pendaftaran baru
     * 
     * @param App\Models\Izin $izin
     * @return array|null
     */
    function doOcrRequestForNewDataRegistation(Izin $izin): ?array;

    /**
     * Mengambil entri data berdasarkan ID izin tertentu
     * 
     * @param int $izin_id ID izin
     * @return array|null
     */
    function getEntriDataByIzinId(int $izin_id): ?array;

}