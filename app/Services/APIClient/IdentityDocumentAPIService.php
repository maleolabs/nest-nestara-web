<?php

namespace App\Services\APIClient;

use App\Services\APIClient\dto\OcrResponse;

interface IdentityDocumentAPIService
{
    public function check(string $contents, string $filename, string $documentType = 'KTP', array $requiredKeys = []): ?OcrResponse;

    public function status(int|string $requestId): ?OcrResponse;
}
