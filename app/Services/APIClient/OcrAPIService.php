<?php

namespace App\Services\APIClient;

use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\dto\OcrResponse;

interface OcrAPIService
{

    /**
     * Mengambil entri data value berdasarkan ID dari izin tertentu
     * 
     * @param int $izin_id ID dari izin
     * @return array Value dari entri data
     */
    function getEntriDataValueByIzinId(int $izin_id): ?OcrResponse;

    /**
     * Mengirimkan data untuk proses OCR
     * 
     * @param OcrRequest $request Data permintaan yang akan dikirim
     * @return void
     */
    function sendOcrRequest(OcrRequest $request): ?OcrResponse;

}