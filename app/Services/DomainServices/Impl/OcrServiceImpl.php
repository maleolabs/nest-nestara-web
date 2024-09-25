<?php

namespace App\Services\DomainServices\Impl;

use App\Exceptions\InvalidTokenException;
use App\Models\Izin;
use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\OcrService;
use App\Services\DomainServices\SyaratIzinService;
use App\Services\Security\TokenService;

class OcrServiceImpl implements OcrService
{

    private TokenService $tokenService;
    private OcrAPIService $ocrAPIService;
    private SyaratIzinService $syaratIzinService;
    private DokumenPemohonService $dokumenPemohonService;

    private $retry_max;
    private $retry_delay;

    public function __construct(
        TokenService $tokenService,
        OcrAPIService $ocrAPIService,
        SyaratIzinService $syaratIzinService,
        DokumenPemohonService $dokumenPemohonService
    ) {
        $this->tokenService = $tokenService;
        $this->ocrAPIService = $ocrAPIService;
        $this->syaratIzinService = $syaratIzinService;
        $this->dokumenPemohonService = $dokumenPemohonService;

        $this->retry_max = config('api-client.ocr_api.retry_max');
        $this->retry_delay = config('api-client.ocr_api.retry_delay');
    }

    public function doOcrRequestForNewDataRegistation(Izin $izin): ?array
    {
        $persyaratan = $this->syaratIzinService->getByTypeAndJenisPerizinan('img', $izin->jenis_perizinan_id);
        if (empty($persyaratan)) return null;

        $url_dokumen = $this->getUrlDokumen($persyaratan, $izin->pemohon_id, $izin->id);
        $requests = $this->getRequests($url_dokumen, $izin->id, $izin->jenis_perizinan_id);

        return $this->sendRequestWithRetry($requests);
    }

    private function getUrlDokumen($persyaratan, $pemohon_id, $izin_id): array
    {
        $url_dokumen = [];
        foreach ($persyaratan as $syarat) {
            $dokumen = $this->dokumenPemohonService->findByPemohonAndSyaratAndIzin($pemohon_id, $syarat->id, $izin_id);
            if ($dokumen) {
                $nama_file = base64_encode($dokumen->nama_file);
                $url = route('api.render-file', ['file_name' => $nama_file]);
                $url_dokumen[$syarat->id] = $url;
            }
        }
        return $url_dokumen;
    }

    private function getRequests($url_dokumen, $izin_id, $jenis_perizinan_id): array
    {
        return array_map(function($syarat_id, $url) use ($izin_id, $jenis_perizinan_id) {
            return OcrRequest::builder()
                ->setIzinId($izin_id)
                ->setJenisPerizinanId($jenis_perizinan_id)
                ->setSyaratIzinId($syarat_id)
                ->setImageUrl($url)
                ->build();
        }, array_keys($url_dokumen), $url_dokumen);
    }

    private function sendRequestWithRetry($requests): array
    {
        $retry_count = 0;
        
        while ($retry_count <= $this->retry_max) {
            try {
                return $this->sendRequest($requests);
            } catch (InvalidTokenException $e) {
                dd($e);
                if ($this->shouldRetry()) {
                    $this->tokenService->generateToken();
                    $retry_count++;
                } else {
                    throw $e;
                }
            }
            sleep($this->retry_delay);
        }

        return [];
    }

    private function shouldRetry(): bool
    {
        $token_invalid = get_pengaturan('CLIENT_TOKEN_INVALID');
        return in_array($token_invalid, ['TOKEN_EXPIRES', 'TOKEN_MISMATCH']);
    }

    private function sendRequest($requests): array
    {
        $check_stats = [
            'total_request' => count($requests),
            'checks_count' => 0,
            'requests' => []
        ];

        foreach ($requests as $request) {
            $response = $this->ocrAPIService->sendOcrRequest($request);
            if ($response) {
                $request_id = $response->getData()['requestId'];
                $check_stats = $this->checkRequestStatus($request_id, $check_stats);
            }
        }

        return $check_stats;
    }

    private function checkRequestStatus($request_id, $check_stats): array
    {
        $request = [
            'request_id' => $request_id,
            'status' => null,
            'duration' => null
        ];

        do {
            $response = $this->ocrAPIService->checkOcrRequestStatus($request_id);
            $status = $response->getData()['status'];
            $duration = $response->getData()['duration'];
            $check_stats['checks_count']++;
            sleep(5);
        } while (in_array($status, ['WAITING', 'PROCESSING']));

        $request['status'] = $status;
        $request['duration'] = $duration . 's';
        $check_stats['requests'][] = $request;
        
        return $check_stats;
    }

}