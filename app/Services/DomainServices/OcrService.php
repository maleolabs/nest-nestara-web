<?php

namespace App\Services\DomainServices;

use App\Models\Izin;

interface OcrService
{

    /**
     * Melakukan request OCR untuk data pendaftaran baru
     * 
     * @param App\Models\Izin $izin
     * @return void
     */
    function doOcrRequestForNewDataRegistation(Izin $izin): void;

}