<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Signed File URLs
    |--------------------------------------------------------------------------
    |
    | Controls the HMAC signature applied to `GET /api/files/{file_name}`
    | by App\Support\SignedFileUrl. The signing key is derived from the
    | application's APP_KEY (no extra secret to manage or rotate here).
    |
    | The endpoint route is protected by the `signed-file` middleware
    | (App\Http\Middleware\VerifySignedFileUrl). Consumers (mobile app,
    | OCR vision service) receive these URLs through API responses, e.g.
    | DokumenPemohonResource, UserResource and OcrServiceImpl.
    |
    */

    // Lifetime of a signed URL in seconds. Used when SignedFileUrl::make()
    // is called without an explicit TTL.
    'ttl' => env('SIGNED_FILE_TTL', 900),

];
