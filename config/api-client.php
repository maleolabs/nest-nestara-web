<?php

return [

    'ocr_api' => [
        'base_url' => env('OCR_API_BASE_URL', 'http://localhost:8080/api/')
    ],

    'region_api' => [
        'base_url' => env('EMSIFA_API_BASE_URL', null)
    ]

];