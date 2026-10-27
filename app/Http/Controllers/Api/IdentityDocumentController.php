<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OcrKtpRequest;
use App\Models\OcrKtpRequest as IdentityDocument;
use App\Services\APIClient\IdentityDocumentAPIService;
use App\Services\DomainServices\Helpers\KtpFieldMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdentityDocumentController extends Controller
{
    public function __construct(private IdentityDocumentAPIService $identityVision) {}

    public function store(OcrKtpRequest $request): JsonResponse
    {
        $startedAt = microtime(true);
        $userId = Auth::guard('api')->id();
        $logContext = ['user_ref' => $this->hashRef($userId)];
        $file = $request->file('image');
        $path = 'private_files/identity-documents/'.Str::random(40).'.'.$file->getClientOriginalExtension();
        if (! Storage::put($path, Crypt::encrypt($file->getContent()))) {
            Log::channel('app')->warning('identity.inbound.store_failed', $logContext + ['duration_ms' => $this->durationMs($startedAt)]);

            return response()->json(['code' => 'storage_unavailable'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        Log::channel('app')->info('identity.inbound.stored', $logContext + ['duration_ms' => $this->durationMs($startedAt)]);
        try {
            $visionStartedAt = microtime(true);
            $response = $this->identityVision->check($file->getContent(), $file->getClientOriginalName(), (string) $request->input('documentType', 'KTP'));
            Log::channel('app')->info('identity.vision.outbound.response', $logContext + ['duration_ms' => $this->durationMs($visionStartedAt), 'http_status' => $response?->getHttpStatus(), 'success' => $response?->isSuccess()]);
        } catch (\Throwable $exception) {
            Log::channel('app')->error('identity.vision.outbound.failure', $logContext + ['duration_ms' => $this->durationMs($visionStartedAt ?? $startedAt), 'exception' => $exception::class]);
            Storage::delete($path);
            throw $exception;
        }
        $data = $response?->getData() ?? [];
        $requestId = $data['requestId'] ?? null;

        if (! $response?->isSuccess() || ! is_string($requestId) || $requestId === '') {
            Log::channel('app')->warning('identity.vision.outbound.failure', $logContext + ['http_status' => $response?->getHttpStatus()]);
            Storage::delete($path);

            return response()->json(['code' => 'vision_unavailable', 'status' => 'failed'], Response::HTTP_BAD_GATEWAY);
        }

        try {
            $record = IdentityDocument::create([
                'user_id' => $userId,
                'request_id' => $requestId,
                'public_check_id' => (string) Str::uuid(),
                'file_path' => $path,
                'status' => KtpFieldMapper::STATUS_PENDING,
            ]);
        } catch (\Throwable $exception) {
            Storage::delete($path);
            throw $exception;
        }

        Log::channel('app')->info('identity.mapped.terminal_result', $logContext + ['request_ref' => $this->hashRef($requestId), 'status' => $record->status, 'duration_ms' => $this->durationMs($startedAt)]);

        return response()->json(['check_id' => $record->public_check_id, 'status' => $record->status], Response::HTTP_ACCEPTED);
    }

    public function status(string $checkId): JsonResponse
    {
        $startedAt = microtime(true);
        $logContext = ['check_ref' => $this->hashRef($checkId)];
        $record = IdentityDocument::where('user_id', Auth::guard('api')->id())->where('public_check_id', $checkId)->first();
        if (! $record) {
            return response()->json(['code' => 'not_found'], Response::HTTP_NOT_FOUND);
        }

        if ($record->status === KtpFieldMapper::STATUS_PENDING) {
            $visionStartedAt = microtime(true);
            $check = $this->identityVision->status($record->request_id);
            Log::channel('app')->info('identity.vision.outbound.status', $logContext + ['request_ref' => $this->hashRef($record->request_id), 'duration_ms' => $this->durationMs($visionStartedAt), 'http_status' => $check?->getHttpStatus(), 'success' => $check?->isSuccess()]);
            if (! $check || ! $check->isSuccess()) {
                $record->refresh();
                if (in_array($record->status, [KtpFieldMapper::STATUS_DONE, KtpFieldMapper::STATUS_MANUAL_REVIEW, KtpFieldMapper::STATUS_REJECTED, KtpFieldMapper::STATUS_FAILED, 'done'], true)) {
                    Log::info('identity_document.status_fallback', [
                        'check_id' => $checkId,
                        'status' => $record->status,
                    ]);

                    return $this->statusResponse($checkId, $record);
                }

                Log::warning('identity_document.vision_unavailable', [
                    'check_id' => $checkId,
                    'request_id_present' => $record->request_id !== null,
                ]);

                return response()->json(['code' => 'vision_unavailable'], Response::HTTP_BAD_GATEWAY);
            }
            $checkData = $check->getData();
            $status = KtpFieldMapper::mapStatus($checkData['decision'] ?? $checkData['status'] ?? null);
            $record->status = $status;
            if (in_array($status, [KtpFieldMapper::STATUS_DONE, KtpFieldMapper::STATUS_MANUAL_REVIEW, KtpFieldMapper::STATUS_REJECTED], true)) {
                // Identity status is source of truth. Never fall back to generic OCR:
                // its result can belong to a different contract/request lifecycle.
                $extracted = $checkData['extractedTexts'] ?? $checkData['extractedFields'] ?? null;
                if (! is_array($extracted) && ! is_object($extracted)) {
                    $extracted = [];
                }
                $extracted = is_object($extracted) ? get_object_vars($extracted) : $extracted;
                if (isset($checkData['documentType']) || isset($checkData['document_type'])) {
                    $extracted['_document_type'] = $checkData['documentType'] ?? $checkData['document_type'];
                }
                $record->result = KtpFieldMapper::mapExtractedTexts($extracted);
            } elseif (in_array($status, [KtpFieldMapper::STATUS_FAILED, KtpFieldMapper::STATUS_REJECTED], true)) {
                $reason = $checkData['reasonCode'] ?? $checkData['reason'] ?? $checkData['decision'] ?? null;
                $record->error = is_string($reason) && $reason !== '' ? Str::limit($reason, 100, '') : 'vision_processing_failed';
            }
            $record->save();
            if ($status !== KtpFieldMapper::STATUS_PENDING) {
                Log::channel('app')->info('identity.mapped.terminal_result', $logContext + ['status' => $status, 'duration_ms' => $this->durationMs($startedAt)]);
            }
            if ($status !== KtpFieldMapper::STATUS_PENDING && $record->file_path) {
                $filePath = $record->file_path;
                try {
                    Storage::delete($filePath);
                    $record->update(['file_path' => null]);
                } catch (\Throwable $exception) {
                    Log::warning('identity_document.cleanup_failed', [
                        'check_id' => $checkId,
                        'status' => $record->status,
                        'exception' => $exception::class,
                    ]);
                }
            }
        }

        return $this->statusResponse($checkId, $record);
    }

    private function hashRef(int|string|null $value): ?string
    {
        return $value === null ? null : hash('sha256', (string) $value);
    }

    private function durationMs(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }

    private function statusResponse(string $checkId, IdentityDocument $record): JsonResponse
    {
        $data = $record->result ?? [];

        return response()->json([
            'check_id' => $checkId,
            'status' => $record->status,
            'data' => $data,
            'document_type' => $data['_document_type'] ?? null,
            'reason_code' => $record->error,
            'profile_unlocked' => $record->status === KtpFieldMapper::STATUS_DONE,
        ]);
    }
}
