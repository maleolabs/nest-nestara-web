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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdentityDocumentController extends Controller
{
    public function __construct(private IdentityDocumentAPIService $identityVision) {}

    public function store(OcrKtpRequest $request): JsonResponse
    {
        $userId = Auth::guard('api')->id();
        $file = $request->file('image');
        $path = 'private_files/identity-documents/'.Str::random(40).'.'.$file->getClientOriginalExtension();
        if (! Storage::put($path, Crypt::encrypt($file->getContent()))) {
            return response()->json(['code' => 'storage_unavailable'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        try {
            $response = $this->identityVision->check($file->getContent(), $file->getClientOriginalName(), (string) $request->input('documentType', 'KTP'));
        } catch (\Throwable $exception) {
            Storage::delete($path);
            throw $exception;
        }
        $data = $response?->getData() ?? [];
        $requestId = $data['requestId'] ?? null;

        if (! $response?->isSuccess() || ! is_string($requestId) || $requestId === '') {
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

        return response()->json(['check_id' => $record->public_check_id, 'status' => $record->status], Response::HTTP_ACCEPTED);
    }

    public function status(string $checkId): JsonResponse
    {
        $record = IdentityDocument::where('user_id', Auth::guard('api')->id())->where('public_check_id', $checkId)->first();
        if (! $record) {
            return response()->json(['code' => 'not_found'], Response::HTTP_NOT_FOUND);
        }

        if ($record->status === KtpFieldMapper::STATUS_PENDING) {
            $check = $this->identityVision->status($record->request_id);
            if (! $check || ! $check->isSuccess()) {
                return response()->json(['code' => 'vision_unavailable'], Response::HTTP_BAD_GATEWAY);
            }
            $checkData = $check->getData();
            $status = KtpFieldMapper::mapStatus($checkData['status'] ?? null);
            $record->status = $status;
            if (in_array($status, [KtpFieldMapper::STATUS_DONE, KtpFieldMapper::STATUS_MANUAL_REVIEW], true)) {
                // Identity status is source of truth. Never fall back to generic OCR:
                // its result can belong to a different contract/request lifecycle.
                $extracted = $checkData['extractedTexts'] ?? $checkData['extractedFields'] ?? null;
                if (! is_array($extracted) && ! is_object($extracted)) {
                    return response()->json(['code' => 'vision_invalid_result'], Response::HTTP_BAD_GATEWAY);
                }
                $extracted = is_object($extracted) ? get_object_vars($extracted) : $extracted;
                if ($extracted === []) {
                    return response()->json(['code' => 'vision_invalid_result'], Response::HTTP_BAD_GATEWAY);
                }
                $record->result = KtpFieldMapper::mapExtractedTexts($extracted);
            } elseif ($status === KtpFieldMapper::STATUS_FAILED) {
                $reason = $checkData['reasonCode'] ?? $checkData['reason'] ?? $checkData['decision'] ?? null;
                $record->error = is_string($reason) && $reason !== '' ? Str::limit($reason, 100, '') : 'vision_processing_failed';
            }
            $record->save();
            if ($status !== KtpFieldMapper::STATUS_PENDING && $record->file_path) {
                Storage::delete($record->file_path);
                $record->update(['file_path' => null]);
            }
        }

        $data = $record->result ?? [];

        return response()->json([
            'check_id' => $checkId,
            'status' => $record->status,
            'data' => $data,
            'profile_unlocked' => KtpFieldMapper::unlocksProfile($record->status, $data),
        ]);
    }
}
