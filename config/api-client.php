<?php

return [

    /**
     * OCR API Client
     * ------------------------------------------------------------------
     * base_url  : fallback env OCR_API_BASE_URL (runtime aktif via DB
     *             get_pengaturan(OCR_BASE_URL) di OcrAPIServiceImpl2).
     * retry_max / retry_delay : env OCR_REQUEST_RETRY_MAX / DELAY,
     *             dipakai untuk retry HTTP OCR. Tidak disimpan di DB.
     * APP_OCR_SETTING (lihat .env.example) adalah feature toggle
     *             Livewire/Admin/Pengaturan.php, bukan bagian array ini.
     */
    'ocr_api' => [
        'base_url' => env('OCR_API_BASE_URL', 'http://localhost:8080/api/'),
        'retry_max' => env('OCR_REQUEST_RETRY_MAX', 3),
        'retry_delay' => env('OCR_REQUEST_RETRY_DELAY', 3),
    ],

    'region_api' => [
        'base_url' => env('EMSIFA_API_BASE_URL', null),
        'get_all_province' => env('GET_ALL_PROVINCE', false)
    ]

];