<?php

namespace App\Services\APIClient\Impl;

use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\IdentityDocumentAPIService;
use App\Services\APIClient\OcrAPIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class IdentityDocumentAPIServiceImpl implements IdentityDocumentAPIService
{
    public function __construct(private OcrAPIService $ocrAPIService) {}

    public function check(string $contents, string $filename, string $documentType): ?OcrResponse
    {
        try {
            $baseUrl = rtrim($this->ocrAPIService->getBaseUrl(), '/');
            $response = Http::withHeaders(array_filter([
                'X-API-KEY' => $this->ocrAPIService->getApiKey(),
                'Accept' => 'application/json',
            ]))->timeout(30)->connectTimeout(5)->attach('image', $contents, $filename)
                ->post($baseUrl.'/identity-document-checks', [
                    'documentType' => $documentType,
                ]);

            $payload = $response->json();
            if (! $response->successful()) {
                Log::channel('app')->warning('vision identity request failed', ['status' => $response->status()]);
            }
            if (! is_array($payload)) {
                return null;
            }

            $data = is_array($payload['data'] ?? null) ? $payload['data'] : $payload;

            return new OcrResponse([
                'requestId' => $data['requestId'] ?? $data['checkId'] ?? $data['id'] ?? null,
                'documentType' => $data['documentType'] ?? $documentType,
                'decision' => $data['decision'] ?? null,
                'reasonCode' => $data['reasonCode'] ?? null,
                'status' => $data['status'] ?? null,
            ], $response->successful(), $response->successful() ? null : ($payload['message'] ?? null));
        } catch (\Throwable $exception) {
            Log::channel('app')->error('identity document check failed', [
                'exception' => $exception::class,
            ]);

            return OcrResponse::failure('vision_unavailable');
        }
    }

    public function status(int|string $requestId): ?OcrResponse
    {
        try {
            $baseUrl = rtrim($this->ocrAPIService->getBaseUrl(), '/');
            $response = Http::withHeaders(['X-API-KEY' => $this->ocrAPIService->getApiKey(), 'Accept' => 'application/json'])
                ->timeout(30)->connectTimeout(5)->get($baseUrl.'/identity-document-checks/'.rawurlencode((string) $requestId));
            $payload = $response->json();
            if (! $response->successful()) {
                Log::channel('app')->warning('vision identity status failed', [
                    'status' => $response->status(), 'request_id' => (string) $requestId,
                    'message' => is_array($payload) ? ($payload['message'] ?? null) : null,
                ]);
            }
            if (! is_array($payload)) {
                return null;
            }
            $data = is_array($payload['data'] ?? null) ? $payload['data'] : $payload;
            // Keep identity status payload intact, including fields returned by Vision.
            // Do not reshape it into generic OCR response semantics.

            return new OcrResponse($data, $response->successful(), $response->successful() ? null : ($payload['message'] ?? null));
        } catch (\Throwable $exception) {
            Log::channel('app')->error('vision identity status failed', ['exception' => $exception::class]);

            return OcrResponse::failure('vision_unavailable');
        }
    }
}
