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

    public function doOcrRequestForNewDataRegistation(Izin $izin): void
    {
        $pemohon_id = $izin->pemohon_id;
        $jenis_perizinan_id = $izin->jenis_perizinan_id;
        $persyaratan = $this->syaratIzinService->getByTypeAndJenisPerizinan('img', $jenis_perizinan_id);
        if (count($persyaratan) == 0) return;

        $url_dokumen = [];
        foreach ($persyaratan as $syarat) {
            $dokumen = $this->dokumenPemohonService->findByPemohonAndSyaratAndIzin($pemohon_id, $syarat->id, $izin->id);
            if ($dokumen) {
                $nama_file = base64_encode($dokumen->nama_file);
                $url = route('api.render-file', ['file_name' => $nama_file]);
                $url_dokumen[$syarat->id] = $url;
            }
        }

        $responses = [];
        foreach ($url_dokumen as $syarat_id => $url) {
            $request = OcrRequest::builder()
                        ->setIzinId($izin->id)
                        ->setJenisPerizinanId($jenis_perizinan_id)
                        ->setSyaratIzinId($syarat_id)
                        ->setImageUrl($url)
                        ->build();
            $response = $this->ocrAPIService->sendOcrRequest($request);
            $responses[] = $response;
        }
    }

}