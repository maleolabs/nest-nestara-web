<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Services\APIClient\dto\OcrRequest;
use App\Services\APIClient\OcrAPIService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\OcrService;
use App\Services\DomainServices\SyaratIzinService;

class OcrServiceImpl implements OcrService
{

    private OcrAPIService $ocrAPIService;

    private SyaratIzinService $syaratIzinService;

    private DokumenPemohonService $dokumenPemohonService;

    public function __construct(
        OcrAPIService $ocrAPIService,
        SyaratIzinService $syaratIzinService,
        DokumenPemohonService $dokumenPemohonService
    ) {
        $this->ocrAPIService = $ocrAPIService;
        $this->syaratIzinService = $syaratIzinService;
        $this->dokumenPemohonService = $dokumenPemohonService;
    }

    public function doOcrRequestForNewDataRegistation(Izin $izin): ?array
    {
        $pemohon_id = $izin->pemohon_id;
        $jenis_perizinan_id = $izin->jenis_perizinan_id;
        $persyaratan = $this->syaratIzinService->getByTypeAndJenisPerizinan('img', $jenis_perizinan_id);
        if (count($persyaratan) == 0) return null;

        $url_dokumen = $this->getUrlDokumen($persyaratan, $pemohon_id, $izin->id);
        $requests = $this->getRequests($url_dokumen, $izin->id, $jenis_perizinan_id);
        return $this->sendRequestRecursively($requests);
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
        $requests = [];
        foreach ($url_dokumen as $syarat_id => $url) {
            $request = OcrRequest::builder()
                        ->setIzinId($izin_id)
                        ->setJenisPerizinanId($jenis_perizinan_id)
                        ->setSyaratIzinId($syarat_id)
                        ->setImageUrl($url)
                        ->build();
            $requests[] = $request;
        }
        return $requests;
    }

    private function sendRequestRecursively($requests): array
    {
        $checks_count = 0;
        $failure_count = 0;
        foreach ($requests as $request) {
            $response = $this->ocrAPIService->sendOcrRequest($request);
            if ($response) {
                $request_id = $response->getData()['requestId'];
                $request_status = $response->getData()['status'];
                do {
                    $response = $this->ocrAPIService->checkOcrRequestStatus($request_id);
                    $request_status = $response->getData()['status'];
                    $checks_count++;
                    sleep(5);
                } while ($request_status == 'WAITING' || $request_status == 'PROCESSING');
                if ($request_status == 'FAILURE') {
                    $failure_count++;
                }
            }
        }
        return [
            'request_count' => count($requests),
            'checks_count' => $checks_count,
            'failure_count' => $failure_count
        ];
    }

}