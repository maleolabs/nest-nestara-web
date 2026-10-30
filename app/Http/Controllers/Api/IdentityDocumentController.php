<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OcrKtpRequest;
use App\Models\OcrKtpRequest as IdentityDocument;
use App\Services\APIClient\IdentityDocumentAPIService;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\Helpers\KtpFieldMapper;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdentityDocumentController extends Controller
{
    public function __construct(private IdentityDocumentAPIService $identityVision, private OcrAPIService $ocr) {}

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
            $response = $this->identityVision->check($file->getContent(), $file->getClientOriginalName(), (string) $request->input('documentType', 'KTP'), KtpFieldMapper::requiredKeys());
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
            $existing = IdentityDocument::where('request_id', $requestId)->first();
            if ($existing) {
                Storage::delete($path);

                return response()->json(['check_id' => $existing->public_check_id, 'status' => $existing->status], Response::HTTP_ACCEPTED);
            }
            $record = IdentityDocument::create([
                'user_id' => $userId,
                'request_id' => $requestId,
                'public_check_id' => (string) Str::uuid(),
                'file_path' => $path,
                'status' => 'verifying',
            ]);
        } catch (QueryException $exception) {
            // Unique request_id protects against concurrent webhook/client retries.
            if ((string) $exception->getCode() === '23000') {
                $existing = IdentityDocument::where('request_id', $requestId)->first();
                if ($existing) {
                    Storage::delete($path);

                    return response()->json(['check_id' => $existing->public_check_id, 'status' => $existing->status], Response::HTTP_ACCEPTED);
                }
            }
            Storage::delete($path);
            throw $exception;
        } catch (\Throwable $exception) {
            Storage::delete($path);
            throw $exception;
        }

        Log::channel('app')->info('identity.mapped.terminal_result', $logContext + ['request_ref' => $this->hashRef($requestId), 'status' => $record->status, 'duration_ms' => $this->durationMs($startedAt)]);

        return response()->json(['check_id' => $record->public_check_id, 'status' => $record->status], Response::HTTP_ACCEPTED);
    }

    public function status(string $checkId): JsonResponse
    {
        return DB::transaction(fn (): JsonResponse => $this->orchestrateStatus($checkId));
    }

    private function orchestrateStatus(string $checkId): JsonResponse
    {
        $startedAt = microtime(true);
        $logContext = ['check_ref' => $this->hashRef($checkId)];
        $record = IdentityDocument::where('user_id', Auth::guard('api')->id())->where('public_check_id', $checkId)->lockForUpdate()->first();
        if (! $record) {
            return response()->json(['code' => 'not_found'], Response::HTTP_NOT_FOUND);
        }

        if (in_array($record->status, ['verifying', KtpFieldMapper::STATUS_PENDING], true)) {
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
            if ($status === KtpFieldMapper::STATUS_DONE) {
                // Single OCR pass: extractedFields sudah dibawa oleh endpoint identity
                // (Vision menyimpan hasil parse dari pass verifikasi yang sama).
                // Tidak ada resubmit ke /ocr/do-ocr untuk alur identitas.
                $status = KtpFieldMapper::STATUS_READY;
            }
            $record->status = $status;
            if (in_array($status, [KtpFieldMapper::STATUS_DONE, KtpFieldMapper::STATUS_READY, KtpFieldMapper::STATUS_MANUAL_REVIEW, KtpFieldMapper::STATUS_REJECTED], true)) {
                // Identity status is source of truth. Never fall back to generic OCR:
                // its result can belong to a different contract/request lifecycle.
                $extracted = $checkData['extractedTexts'] ?? $checkData['extractedFields'] ?? null;
                $extracted = is_array($extracted) || is_object($extracted)
                    ? (is_object($extracted) ? get_object_vars($extracted) : $extracted)
                    : [];
                if (isset($checkData['documentType']) || isset($checkData['document_type'])) {
                    $extracted['_document_type'] = $checkData['documentType'] ?? $checkData['document_type'];
                }
                $record->result = KtpFieldMapper::mapExtractedTexts($extracted);
                // extracted == [] hanya ketika Vision tidak mengirim fields apa pun;
                // injeksi _document_type tidak dihitung sebagai data hasilOCR.
                $realFields = array_filter(
                    is_array($record->result) ? $record->result : [],
                    fn ($value, $key) => $key !== '_document_type' && $key !== '_document_type_normalized' && $value !== null,
                    ARRAY_FILTER_USE_BOTH
                );
                if ($status === KtpFieldMapper::STATUS_READY && $realFields === []) {
                    $status = $record->status = 'extraction_failed';
                    $record->error = 'ocr_result_unavailable';
                }
            }
            // manual_review butuh reason_code agar client bisa menampilkan pesan bermakna.
            if (in_array($status, [KtpFieldMapper::STATUS_FAILED, KtpFieldMapper::STATUS_REJECTED, KtpFieldMapper::STATUS_MANUAL_REVIEW], true)) {
                $reason = $checkData['reasonCode'] ?? $checkData['reason'] ?? $checkData['decision'] ?? null;
                $record->error = is_string($reason) && $reason !== '' ? Str::limit($reason, 100, '') : 'vision_processing_failed';
            }
            $record->save();
            if ($status !== KtpFieldMapper::STATUS_PENDING) {
                Log::channel('app')->info('identity.mapped.terminal_result', $logContext + ['status' => $status, 'duration_ms' => $this->durationMs($startedAt)]);
            }
            if (in_array($status, [KtpFieldMapper::STATUS_READY, KtpFieldMapper::STATUS_MANUAL_REVIEW, KtpFieldMapper::STATUS_REJECTED, KtpFieldMapper::STATUS_FAILED, 'extraction_failed'], true) && $record->file_path) {
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

        if ($record->status === 'extracting' && $record->extraction_request_id) {
            $check = $this->ocr->checkOcrRequestStatus($record->extraction_request_id);
            $checkData = $check?->getData() ?? [];
            $upstream = KtpFieldMapper::mapStatus($checkData['status'] ?? $checkData['decision'] ?? null);
            if ($upstream === KtpFieldMapper::STATUS_DONE) {
                $result = $this->ocr->getOcrResultByRequestId($record->extraction_request_id);
                if ($result?->isSuccess()) {
                    $resultData = $result->getData();
                    $extracted = $resultData['extractedTexts'] ?? $resultData['extractedFields'] ?? $resultData['result'] ?? [];
                    if (! is_array($extracted) && ! is_object($extracted)) {
                        $extracted = [];
                    }
                    if (is_object($extracted)) {
                        $extracted = get_object_vars($extracted);
                    }
                    if ($extracted === []) {
                        $record->update(['status' => 'extraction_failed', 'error' => 'ocr_result_empty']);
                        $this->deleteStoredFile($record, $checkId);
                        $record->refresh();

                        return $this->statusResponse($checkId, $record);
                    }
                    $record->update(['status' => 'ready', 'result' => KtpFieldMapper::mapExtractedTexts($extracted), 'error' => null]);
                    $this->deleteStoredFile($record, $checkId);
                } else {
                    $record->update(['status' => 'extraction_failed', 'error' => 'ocr_result_unavailable']);
                    $this->deleteStoredFile($record, $checkId);
                }
            } elseif ($upstream === KtpFieldMapper::STATUS_FAILED) {
                $record->update(['status' => 'extraction_failed', 'error' => 'ocr_processing_failed']);
                $this->deleteStoredFile($record, $checkId);
            }
            $record->refresh();
        }

        return $this->statusResponse($checkId, $record);
    }

    private function beginExtraction(IdentityDocument $record): string
    {
        if ($record->extraction_request_id) {
            return 'extracting';
        }
        try {
            $contents = Crypt::decrypt(Storage::get($record->file_path));
            $response = $this->ocr->sendKtpImageRequest($contents, basename($record->file_path), KtpFieldMapper::requiredKeys());
            $requestId = $response?->getData()['requestId'] ?? null;
            if (! $response?->isSuccess() || ! is_string($requestId) || $requestId === '') {
                $record->update(['status' => 'extraction_failed', 'error' => 'ocr_unavailable']);

                return 'extraction_failed';
            }
            $record->update(['extraction_request_id' => $requestId, 'status' => 'extracting']);

            return 'extracting';
        } catch (\Throwable) {
            $record->update(['status' => 'extraction_failed', 'error' => 'ocr_unavailable']);

            return 'extraction_failed';
        }
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
        $data = is_array($record->result) ? $record->result : [];

        return response()->json([
            'check_id' => $checkId,
            'status' => $record->status,
            'data' => $data,
            'document_type' => $data['_document_type'] ?? null,
            'reason_code' => $record->error,
            'ready' => $record->status === 'ready',
            'profile_unlocked' => KtpFieldMapper::unlocksProfile($record->status, $data),
        ]);
    }

    private function deleteStoredFile(IdentityDocument $record, string $checkId): void
    {
        if (! $record->file_path) {
            return;
        }
        $path = $record->file_path;
        try {
            Storage::delete($path);
            $record->update(['file_path' => null]);
        } catch (\Throwable $exception) {
            Log::warning('identity_document.cleanup_failed', ['check_id' => $checkId, 'status' => $record->status, 'exception' => $exception::class]);
        }
    }
}
