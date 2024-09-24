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
     * @return OcrResponse|null
     */
    function getEntriDataValueByIzinId(int $izin_id): ?OcrResponse;

    /**
     * Mengirimkan data untuk proses OCR
     * 
     * @param OcrRequest $request Data permintaan yang akan dikirim
     * @return OcrResponse|null
     */
    function sendOcrRequest(OcrRequest $request): ?OcrResponse;

    /**
     * Mengecek status dari request OCR
     * 
     * @param int $request_id ID dari request
     * @return OcrResponse|null
     */
    function checkOcrRequestStatus(int $request_id): ?OcrResponse;

}