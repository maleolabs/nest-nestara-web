<?php

namespace App\Services\DomainServices\Impl;

use App\Exceptions\InvalidTokenException;
use App\Models\Izin;
use App\Models\OcrRequest as OcrRequestModel;
use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\OcrService;
use App\Services\DomainServices\SyaratIzinService;
use App\Services\Security\TokenService;
use App\Support\SignedFileUrl;
use Illuminate\Database\QueryException;

class OcrServiceImpl implements OcrService
{
    private TokenService $tokenService;

    private OcrAPIService $ocrAPIService;

    private SyaratIzinService $syaratIzinService;

    private DokumenPemohonService $dokumenPemohonService;

    private EntriDataService $entriDataService;

    private $retry_max;

    private $retry_delay;

    public function __construct(
        TokenService $tokenService,
        OcrAPIService $ocrAPIService,
        SyaratIzinService $syaratIzinService,
        DokumenPemohonService $dokumenPemohonService,
        EntriDataService $entriDataService
    ) {
        $this->tokenService = $tokenService;
        $this->ocrAPIService = $ocrAPIService;
        $this->syaratIzinService = $syaratIzinService;
        $this->dokumenPemohonService = $dokumenPemohonService;
        $this->entriDataService = $entriDataService;

        $this->retry_max = config('api-client.ocr_api.retry_max');
        $this->retry_delay = config('api-client.ocr_api.retry_delay');
    }

    public function doOcrRequestForNewDataRegistation(Izin $izin): ?array
    {
        $persyaratan = $this->syaratIzinService->getByTypeAndJenisPerizinan('img', $izin->jenis_perizinan_id);
        if (empty($persyaratan)) {
            return null;
        }

        $entri_data = $this->entriDataService->getByJenisPerizinanId($izin->jenis_perizinan_id);
        $required_keys = array_column($entri_data, 'nama');

        // getUrlDokumen juga meng-claim baris OcrRequest (status pending → processing);
        // tombol skip idempotency: syarat yang sudah done / in-progress tidak dikirim ulang.
        [$url_dokumen, $ocr_rows] = $this->getUrlDokumen($persyaratan, $izin->pemohon_id, $izin->id);
        if (empty($url_dokumen)) {
            return null;
        }

        $requests = $this->getRequests($url_dokumen, $izin->id, $izin->jenis_perizinan_id, $required_keys);

        return $this->sendRequest($requests, $ocr_rows);
    }

    public function getEntriDataByIzinId(int $izin_id): ?array
    {
        return $this->excecuteWithRetry(function () use ($izin_id) {
            $data = $this->ocrAPIService->getEntriDataValueByIzinId($izin_id)?->getData()['extractedTexts'] ?? [];

            if (is_object($data)) {
                $data = get_object_vars($data);

                return $data;
            }

            return array_column($data, 'textValue', 'textKey');
        });
    }

    private function getUrlDokumen($persyaratan, $pemohon_id, $izin_id): array
    {
        $url_dokumen = [];
        $ocr_rows = [];
        foreach ($persyaratan as $syarat) {
            $dokumen = $this->dokumenPemohonService->findByPemohonAndSyaratAndIzin($pemohon_id, $syarat->id, $izin_id);
            if ($dokumen) {
                // klaim baris OCR untuk triple (pemohon, syarat, izin);
                // null berarti sudah done / in-progress milik attempt lain → skip syarat ini
                $claimed = $this->claimOcrRequestRow($pemohon_id, $syarat->id, $izin_id);
                if (! $claimed) {
                    continue;
                }

                $url_dokumen[$syarat->id] = SignedFileUrl::make($dokumen->nama_file);
                $ocr_rows[$syarat->id] = $claimed;
            }
        }

        return [$url_dokumen, $ocr_rows];
    }

    /**
     * Klaim baris OcrRequest untuk dikirim ke vision. Baris dibuat persist-first
     * di PerizinanServiceImpl@requestOcrForNewRegistration; di sini dibuat ulang
     * (firstOrCreate) hanya sebagai jaring pengaman job replay/manual.
     * Setelah klaim sukses status = processing; request_id diisi setelah vision menerima.
     */
    private function claimOcrRequestRow(int $pemohon_id, int $syarat_izin_id, int $izin_id): ?OcrRequestModel
    {
        try {
            $row = OcrRequestModel::firstOrCreate(
                ['izin_id' => $izin_id, 'syarat_izin_id' => $syarat_izin_id],
                ['pemohon_id' => $pemohon_id, 'status' => OcrRequestModel::STATUS_PENDING]
            );
        } catch (QueryException $e) {
            // dua worker berebut + unique index (izin_id, syarat_izin_id): milik yang lain
            return null;
        }

        if ($row->isBlockedForResend()) {
            return null;
        }

        $row->status = OcrRequestModel::STATUS_PROCESSING;
        $row->request_id = null;
        $row->save();

        return $row;
    }

    private function markOcrRequestRow(OcrRequestModel $row, string $status): void
    {
        $row->status = $status;
        $row->save();
    }

    private function getRequests($url_dokumen, $izin_id, $jenis_perizinan_id, $required_keys): array
    {
        return array_map(function ($syarat_id, $url) use ($izin_id, $jenis_perizinan_id, $required_keys) {
            return OcrRequest::builder()
                ->setIzinId($izin_id)
                ->setJenisPerizinanId($jenis_perizinan_id)
                ->setSyaratIzinId($syarat_id)
                ->setImageUrl($url)
                ->setRequiredKeys($required_keys)
                ->build();
        }, array_keys($url_dokumen), $url_dokumen);
    }

    private function sendRequestWithRetry($requests): array
    {
        return $this->excecuteWithRetry(function () use ($requests) {
            return $this->sendRequest($requests);
        });
    }

    private function sendRequest($requests, $ocr_rows): array
    {
        $check_stats = [
            'total_request' => count($requests),
            'checks_count' => 0,
            'requests' => [],
        ];

        foreach ($requests as $request) {
            $response = $this->ocrAPIService->sendOcrRequest($request);
            $row = $ocr_rows[$request->getSyaratIzinId()] ?? null;

            if ($response) {
                $request_id = $response->getData()['requestId'];
                // request_id ditulis ke baris idempotency-nya sendiri; OcrAPIServiceImpl2
                // tidak lagi membuat baris duplikat (persist-first milik pemanggil)
                if ($row) {
                    $row->request_id = $request_id;
                    $row->save();
                }
                $check_stats = $this->checkRequestStatus($request_id, $check_stats, $row);
            } elseif ($row) {
                // vision tidak menerima request pada attempt ini → masih retryable
                $this->markOcrRequestRow($row, OcrRequestModel::STATUS_FAILED);
            }
        }

        return $check_stats;
    }

    private function checkRequestStatus($request_id, $check_stats, ?OcrRequestModel $row = null): array
    {
        $request = [
            'request_id' => $request_id,
            'status' => null,
            'duration' => null,
        ];

        do {
            $response = $this->ocrAPIService->checkOcrRequestStatus($request_id);
            $status = $response->getData()['status'] ?? null;
            $duration = $response->getData()['duration'];
            $check_stats['checks_count']++;
            sleep(5);
        } while (in_array($status, ['WAITING', 'PROCESSING']));

        // asumsi kontrak vision: WAITING/PROCESSING in-flight; DONE sukses;
        // selain itu (mis. FAILED) = gagal (retryable sampai --tries habis → failed_final).
        $result = $status === 'DONE'
            ? $this->ocrAPIService->getOcrResultByRequestId($request_id)
            : null;
        $hasResult = $result?->isSuccess() === true
            && $this->hasExtractedTexts($result->getData()['extractedTexts'] ?? null);

        if ($row && $status === 'DONE' && $hasResult) {
            $this->markOcrRequestRow($row, OcrRequestModel::STATUS_DONE);
        } elseif ($row && ($status === 'DONE' || in_array($status, ['FAILED', 'FAILURE'], true))) {
            $this->markOcrRequestRow($row, OcrRequestModel::STATUS_FAILED);
        }

        $request['status'] = $status;
        // duration hanya tersedia pada status sukses (kontrak vision: Float saat DONE);
        // failure path mengirim null — jangan diformat jadi string "s"
        $request['duration'] = is_numeric($duration) ? $duration.'s' : null;
        $check_stats['requests'][] = $request;

        return $check_stats;
    }

    private function hasExtractedTexts(mixed $value): bool
    {
        if (is_object($value)) {
            $value = get_object_vars($value);
        }

        return is_array($value) && $value !== [];
    }

    private function excecuteWithRetry(callable $callback): ?array
    {
        $retry_count = 0;
        while ($retry_count <= $this->retry_max) {
            try {
                return $callback();
            } catch (InvalidTokenException $e) {
                if ($this->shouldRetry()) {
                    $this->tokenService->generateToken();
                    $retry_count++;
                } else {
                    throw $e;
                }
            }
            sleep($this->retry_delay);
        }

        return null;
    }

    private function shouldRetry(): bool
    {
        $token_invalid = get_pengaturan('CLIENT_TOKEN_INVALID');

        return in_array($token_invalid, ['TOKEN_EXPIRES', 'TOKEN_MISMATCH']);
    }
}
