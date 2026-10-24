<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OcrKtpRequest;
use App\Models\OcrKtpRequest as ModelsOcrKtpRequest;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\Helpers\KtpFieldMapper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OcrController extends Controller
{
    private const KTP_FOLDER = 'ocr_ktp';

    private const TOTAL_TIMEOUT_SECONDS = 8;

    private const POLL_INTERVAL_SECONDS = 2;

    private OcrAPIService $ocrAPIService;

    public function __construct(OcrAPIService $ocrAPIService)
    {
        $this->ocrAPIService = $ocrAPIService;
    }

    /**
     * Terima unggahan foto KTP dari mobile, simpan secara privat,
     * teruskan ke vision service untuk OCR, lalu kembalikan field
     * KTP terstruktur. Jika proses belum selesai dalam batas waktu,
     * kembalikan status pending beserta ocr_request_id untuk polling.
     */
    public function store(OcrKtpRequest $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            $file = $request->file('image');

            [$path, $contents] = $this->storeAndGetContents($file);

            $response = $this->ocrAPIService->sendKtpImageRequest(
                $contents,
                $file->getClientOriginalName(),
                KtpFieldMapper::requiredKeys()
            );

            if (! $response || ! $response->isSuccess()) {
                // catat pesan error upstream (tanpa header/URL/kredensial)
                // sebelum membalas dengan pesan generik
                Log::channel('app')->warning('vision service menolak permintaan OCR KTP', [
                    'error_message' => $response?->getErrorMessage(),
                ]);
                $this->deleteStoredFile($path);

                return $this->serviceUnavailable();
            }

            $data = $response->getData();
            $request_id = $data['requestId'] ?? null;
            if (empty($request_id)) {
                Log::channel('app')->warning('vision service tidak mengembalikan requestId untuk OCR KTP');
                $this->deleteStoredFile($path);

                return $this->serviceUnavailable();
            }

            ModelsOcrKtpRequest::create([
                'user_id' => $user->id,
                'request_id' => $request_id,
                'file_path' => $path,
            ]);

            $status = $this->pollUntilFinished($request_id);
            $fields = [];
            if ($status === KtpFieldMapper::STATUS_DONE) {
                $fields = $this->fetchDoneFields($request_id) ?? [];
            }

            // Retensi: file KTP dihapus begitu alur mencapai kondisi terminal
            // (done/failed) karena vision sudah menerima byte gambar saat
            // submission. Untuk pending, file dipertahankan dan akan dihapus
            // di endpoint status() saat hasil terminal berhasil diambil.
            if ($status !== KtpFieldMapper::STATUS_PENDING) {
                $this->deleteStoredFile($path);
            }

            return response()->json([
                'data' => $fields,
                'ocr_request_id' => $request_id,
                'status' => $status,
                'profile_unlocked' => KtpFieldMapper::unlocksProfile($status, $fields),
            ]);
        } catch (Exception $e) {
            Log::channel('app')->error('Gagal memproses OCR KTP', ['error' => $e->getMessage()]);

            return $this->serviceUnavailable();
        }
    }

    /**
     * Cek status / ambil hasil OCR KTP berdasarkan request id vision.
     * Hanya pemilik request (user yang mengunggah) yang boleh mengakses.
     */
    public function status(string $request_id): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();

            $record = ModelsOcrKtpRequest::where('user_id', $user->id)
                ->where('request_id', $request_id)
                ->first();
            if (! $record) {
                return response()->json([
                    'success' => false,
                    'message' => 'hasil OCR tidak ditemukan',
                ], Response::HTTP_NOT_FOUND);
            }

            $check = $this->ocrAPIService->checkOcrRequestStatus($request_id);
            if (! $check) {
                return $this->serviceUnavailable();
            }
            if (! $check->isSuccess()) {
                return response()->json([
                    'success' => false,
                    'message' => 'hasil OCR tidak ditemukan',
                ], Response::HTTP_NOT_FOUND);
            }

            $status = KtpFieldMapper::mapStatus($check->getData()['status'] ?? null);

            $fields = [];
            $delete_file = false;
            if ($status === KtpFieldMapper::STATUS_DONE) {
                $fetched = $this->fetchDoneFields($request_id);
                $delete_file = $fetched !== null; // jangan hapus jika get-result gagal transien
                $fields = $fetched ?? [];
            } elseif ($status === KtpFieldMapper::STATUS_FAILED) {
                // FAILURE sudah konfirmasi terminal dari vision, file tak dibutuhkan lagi
                $delete_file = true;
            }

            if ($delete_file) {
                $this->deleteStoredFile($record->file_path);
            }

            return response()->json([
                'data' => $fields,
                'ocr_request_id' => $request_id,
                'status' => $status,
                'profile_unlocked' => KtpFieldMapper::unlocksProfile($status, $fields),
            ]);
        } catch (Exception $e) {
            Log::channel('app')->error('Gagal mengecek status OCR KTP', ['error' => $e->getMessage()]);

            return $this->serviceUnavailable();
        }
    }

    /**
     * Simpan gambar KTP ke storage privat terenkripsi dengan nama file
     * acak (Str::random(40)) khusus folder ocr_ktp — nama asli klien
     * tidak dipakai agar tidak bisa ditebak dan bebas bentrok.
     * Pola enkripsi mengikuti FileServiceImpl::storeEncryptedFile.
     *
     * @return array{0: string, 1: string} [path tersimpan, isi asli file]
     */
    private function storeAndGetContents($file): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        if (! in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $extension = 'jpg';
        }

        $contents = $file->getContent();
        $path = 'private_files/'.self::KTP_FOLDER.'/'.Str::random(40).'.'.$extension;

        Storage::put($path, Crypt::encrypt($contents));

        return [$path, $contents];
    }

    /**
     * Polling status vision sampai selesai atau habis batas waktu total.
     *
     * @return string done|pending|failed
     */
    private function pollUntilFinished(string $request_id): string
    {
        $deadline = microtime(true) + self::TOTAL_TIMEOUT_SECONDS;
        $status = KtpFieldMapper::STATUS_PENDING;

        while (microtime(true) < $deadline) {
            sleep(self::POLL_INTERVAL_SECONDS);

            $check = $this->ocrAPIService->checkOcrRequestStatus($request_id);
            if ($check) {
                $status = KtpFieldMapper::mapStatus($check->getData()['status'] ?? null);
                if ($status !== KtpFieldMapper::STATUS_PENDING) {
                    break;
                }
            }
        }

        return $status;
    }

    /**
     * Ambil field hasil OCR untuk status done.
     * Mengembalikan null jika get-result gagal (mis. gangguan transien).
     *
     * @return array<string, string>|null
     */
    private function fetchDoneFields(string $request_id): ?array
    {
        $result = $this->ocrAPIService->getOcrResultByRequestId($request_id);
        if (! $result || ! $result->isSuccess()) {
            return null;
        }

        return KtpFieldMapper::mapExtractedTexts($result->getData()['extractedTexts'] ?? null);
    }

    /**
     * Hapus file KTP dari storage privat; aman dipanggil berulang.
     */
    private function deleteStoredFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        if (! Storage::delete($path)) {
            Log::channel('app')->warning('Gagal menghapus file KTP dari storage', ['path' => $path]);
        }
    }

    private function serviceUnavailable(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'layanan OCR tidak dapat dihubungi, silakan coba beberapa saat lagi',
        ], Response::HTTP_BAD_GATEWAY);
    }
}
