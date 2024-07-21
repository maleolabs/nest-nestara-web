<?php

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;

if (!function_exists('countByProcess')) {
    function countByProcess($process)
    {
        $perizinanService = app(PerizinanService::class);
        $status = getProcessStatus($process);
        $progress = getProcessProgress($process);
        $keterangan = getProcessKeterangan($process);

        $count = $perizinanService->getCount($status, $progress, $keterangan);

        return $count > 0 ? $count : '';
    }
}

if (!function_exists('getProcessStatus')) {
    function getProcessStatus($process): array
    {
        $statusMap = [
            'pendaftaran' => [StatusIzinEnum::DIDAFTARKAN, StatusIzinEnum::DITINJAU, StatusIzinEnum::REVISI_PERSYARATAN],
            'pendataan' => [StatusIzinEnum::PROSES],
            'rekomendasi' => [StatusIzinEnum::PROSES],
            'penetapan' => [StatusIzinEnum::PROSES],
            'penyerahan' => [StatusIzinEnum::PENERBITAN, StatusIzinEnum::PENYERAHAN],
        ];

        return $statusMap[$process] ?? [];
    }
}

if (!function_exists('getProcessProgress')) {
    function getProcessProgress($process): array
    {
        $defaultProgress = [
            StatusIzinProgressEnum::UNPROCESSED,
            StatusIzinProgressEnum::PROCESSING,
            StatusIzinProgressEnum::FINISHED
        ];

        $progressMap = [
            'pendaftaran' => $defaultProgress,
            'pendataan' => $defaultProgress,
            'rekomendasi' => $defaultProgress,
            'penetapan' => $defaultProgress,
            'penyerahan' => $defaultProgress,
        ];

        return $progressMap[$process] ?? [];
    }
}

if (!function_exists('getProcessKeterangan')) {
    function getProcessKeterangan($process): array
    {
        $keteranganMap = [
            'pendaftaran' => [],
            'pendataan' => [
                BackOfficeStatusEnum::BELUM_ENTRI,
                BackOfficeStatusEnum::SEDANG_ENTRI,
                BackOfficeStatusEnum::SUDAH_ENTRI,
                BackOfficeStatusEnum::ENTRI_TINJAUAN_LAPANGAN,
                BackOfficeStatusEnum::SUDAH_ENTRI_TINJAUAN_LAPANGAN,
            ],
            'rekomendasi' => [
                BackOfficeStatusEnum::TIDAK_DIREKOM
            ],
            'penetapan' => [
                BackOfficeStatusEnum::DIREKOM,
                BackOfficeStatusEnum::REKOM_CATATAN,
                BackOfficeStatusEnum::DIIZINKAN,
                BackOfficeStatusEnum::TIDAK_DIIZINKAN,
                BackOfficeStatusEnum::LAYANAN_DITOLAK
            ]
        ];

        return $keteranganMap[$process] ?? [];
    }
}