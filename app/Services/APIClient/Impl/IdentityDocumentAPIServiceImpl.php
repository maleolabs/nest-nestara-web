<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\IdentityDocumentAPIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class IdentityDocumentAPIServiceImpl implements IdentityDocumentAPIService
{
    public function check(string $contents, string $filename, string $documentType): ?OcrResponse
    {
        try {
            $response = Http::withHeaders(array_filter([
                'X-API-KEY' => config('services.vision.credential'),
                'Accept' => 'application/json',
            ]))->timeout(30)->connectTimeout(5)->attach('image', $contents, $filename)
                ->post(rtrim((string) config('services.vision.base_url'), '/').'/api/identity-document-checks', [
                    'documentType' => $documentType,
                ]);

            $payload = $response->json();
            if (! is_array($payload)) {
                return null;
            }

            return new OcrResponse([
                'checkId' => $payload['checkId'] ?? $payload['id'] ?? null,
                'documentType' => $payload['documentType'] ?? $documentType,
                'decision' => $payload['decision'] ?? null,
                'reasonCode' => $payload['reasonCode'] ?? null,
                'status' => $payload['status'] ?? null,
            ], $response->successful(), $response->successful() ? null : ($payload['message'] ?? null));
        } catch (\Throwable $exception) {
            Log::channel('app')->error('identity document check failed', [
                'exception' => $exception::class,
            ]);

            return OcrResponse::failure('vision_unavailable');
        }
    }
}
