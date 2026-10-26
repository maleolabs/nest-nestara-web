<?php

namespace App\Services\APIClient;

use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\dto\OcrResponse;

interface OcrAPIService
{
    public function getApiKey(): string;

    public function getBaseUrl(): string;

    /**
     * Mengambil entri data value berdasarkan ID dari izin tertentu
     *
     * @param  int  $izin_id  ID dari izin
     */
    public function getEntriDataValueByIzinId(int $izin_id): ?OcrResponse;

    /**
     * Mengirimkan data untuk proses OCR
     *
     * @param  OcrRequest  $request  Data permintaan yang akan dikirim
     */
    public function sendOcrRequest(OcrRequest $request, ?string $token = null): ?OcrResponse;

    /**
     * Mengecek status dari request OCR
     *
     * @param  int|string  $request_id  ID dari request
     */
    public function checkOcrRequestStatus(int|string $request_id): ?OcrResponse;

    /**
     * Mengirim gambar KTP (isi file) untuk proses OCR tanpa terkait izin.
     * Berbeda dengan sendOcrRequest: tidak membuat record ocr_requests
     * (tidak ada izin_id) dan mengirim file pada part "image" sesuai
     * kontrak vision service.
     *
     * @param  string  $image_contents  isi file gambar KTP
     * @param  string|null  $original_file_name  nama asli file gambar
     * @param  array<string>  $required_keys  keys hasil ekstraksi yang diminta
     */
    public function sendKtpImageRequest(string $image_contents, ?string $original_file_name, array $required_keys = []): ?OcrResponse;

    /**
     * Mengambil hasil OCR berdasarkan request id dari vision service.
     *
     * @param  int|string  $request_id  ID dari request
     */
    public function getOcrResultByRequestId(int|string $request_id): ?OcrResponse;
}
