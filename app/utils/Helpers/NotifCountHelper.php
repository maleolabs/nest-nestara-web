<?php

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;

if (!function_exists('countByProcess')) {
    function countByProcess($process)
    {
        // memo per-request — badge dipanggil berkali-kali di layout
        static $memo = [];

        if (array_key_exists($process, $memo)) {
            return $memo[$process];
        }

        $perizinanService = app(PerizinanService::class);
        $status = getProcessStatus($process);
        $progress = getProcessProgress($process);
        $keterangan = getProcessKeterangan($process);

        // cache singkat antar request — badge tidak perlu real-time ketat
        $count = cache()->remember(
            "notif-count:{$process}:" . md5(serialize([$status, $progress, $keterangan])),
            now()->addSeconds(30),
            fn () => $perizinanService->getCount($status, $progress, $keterangan)
        );

        return $memo[$process] = $count > 0 ? $count : '';
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
            ]
        ];

        return $keteranganMap[$process] ?? [];
    }
}
