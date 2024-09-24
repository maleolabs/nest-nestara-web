<?php

return [

    'ocr_api' => [
        'base_url' => env('OCR_API_BASE_URL', 'http://localhost:8080/api/'),
        'retry_max' => env('OCR_REQUEST_RETRY_MAX', 3),
        'retry_delay' => env('OCR_REQUEST_RETRY_DELAY', 3)
    ],

    'region_api' => [
        'base_url' => env('EMSIFA_API_BASE_URL', null)
    ]

];