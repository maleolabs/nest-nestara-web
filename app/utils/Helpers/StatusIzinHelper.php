<?php

use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;

if (!function_exists('getStatusIzinClasses')) {
    function getStatusIzinClasses($status)
    {
        $statusClass = [
            StatusIzinProgressEnum::FINISHED => $status->nama != StatusIzinEnum::BATAL ? 'success' : 'danger',
            StatusIzinProgressEnum::UNPROCESSED => 'muted',
            StatusIzinProgressEnum::PROCESSING => 'dark',
            StatusIzinProgressEnum::CANCELED => 'danger',
            StatusIzinProgressEnum::ERROR => 'warning',
        ];

        $textClass = "text-{$statusClass[$status->progres]}";
        $bgClass = "bg-{$statusClass[$status->progres]}";

        return [
            'textClass' => $textClass,
            'bgClass' => $bgClass,
        ];
    }
}

if (!function_exists('getStatusIzinProcessClasses')) {
    function getStatusIzinProcessClasses($status)
    {
        $statusClass = [
            BackOfficeStatusEnum::BELUM_ENTRI => 'muted',
            BackOfficeStatusEnum::SEDANG_ENTRI => 'dark',
            BackOfficeStatusEnum::SUDAH_ENTRI => 'info',
            BackOfficeStatusEnum::PERUBAHAN_ENTRI => 'warning',
            BackOfficeStatusEnum::ENTRI_TINJAUAN_LAPANGAN => 'dark',
            BackOfficeStatusEnum::SUDAH_ENTRI_TINJAUAN_LAPANGAN =>'success',
            BackOfficeStatusEnum::TIM_TEKNIS => 'dark',
        ];

        $textClass = "text-{$statusClass[$status->keterangan]}";
        $bgClass = "bg-{$statusClass[$status->keterangan]}";

        return [
            'textClass' => $textClass,
            'bgClass' => $bgClass,
        ];
    }
}

if (!function_exists('renderStatusIzinWithIcon')) {
    function renderStatusIzinWithIcon($status)
    {
        $icon = '';
        $textColor = '';

        if ($status->progres == StatusIzinProgressEnum::FINISHED && $status->nama != StatusIzinEnum::BATAL) {
            $icon = '<i class="fa-regular fa-circle-check mt-1 text-success"></i>';
            $textColor = 'text-success';
        } elseif ($status->progres == StatusIzinProgressEnum::UNPROCESSED) {
            $icon = $status->nama == StatusIzinEnum::PROSES ? '<i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>' : '<i class="fa-solid fa-circle-minus mt-1"></i>';
            $textColor = 'text-muted';
        } elseif ($status->progres == StatusIzinProgressEnum::PROCESSING) {
            $icon = '<i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>';
            $textColor = 'text-dark';
        } elseif ($status->progres == StatusIzinProgressEnum::CANCELED || $status->nama == StatusIzinEnum::BATAL) {
            $icon = '<i class="fa-solid fa-circle-xmark mt-1 text-danger"></i>';
            $textColor = 'text-danger';
        } elseif ($status->progres == StatusIzinProgressEnum::ERROR) {
            $icon = '<i class="fa-solid fa-triangle-exclamation mt-1 text-warning"></i>';
            $textColor = 'text-warning';
        }

        $html = <<<HTML
            <div class="d-flex align-items-top gap-2 my-3">
                {$icon}
                <div>
                    <p class="mb-0 {$textColor}">
                        {$status->nama}
                    </p>
                    <p class="mb-0">{$status->created_at->translatedFormat('l, d F Y H:i')} WITA</p>
                </div>
            </div>
        HTML;
        return $html;
    }
}

if (!function_exists('renderStatusIzinWithIconAndDetails')) {
    function renderStatusIzinWithIconAndDetails($status, $can_read_progres = false, $can_read_keterangan = false, $can_read_pegawai = false)
    {
        $icon = '';
        $textColor = '';

        if ($status->progres == StatusIzinProgressEnum::FINISHED && $status->nama != StatusIzinEnum::BATAL) {
            $icon = '<i class="fa-regular fa-circle-check mt-1 text-success"></i>';
            $textColor = 'text-success';
        } elseif ($status->progres == StatusIzinProgressEnum::UNPROCESSED) {
            $icon = $status->nama == StatusIzinEnum::PROSES ? '<i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>' : '<i class="fa-solid fa-circle-minus mt-1"></i>';
            $textColor = 'text-muted';
        } elseif ($status->progres == StatusIzinProgressEnum::PROCESSING) {
            $icon = '<i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>';
            $textColor = 'text-dark';
        } elseif ($status->progres == StatusIzinProgressEnum::CANCELED || $status->nama == StatusIzinEnum::BATAL) {
            $icon = '<i class="fa-solid fa-circle-xmark mt-1 text-danger"></i>';
            $textColor = 'text-danger';
        } elseif ($status->progres == StatusIzinProgressEnum::ERROR) {
            $icon = '<i class="fa-solid fa-triangle-exclamation mt-1 text-warning"></i>';
            $textColor = 'text-warning';
        }

        $namaStatus = $can_read_progres && $status->nama != App\utils\enums\StatusIzinEnum::DIDAFTARKAN && $status->nama != App\utils\enums\StatusIzinEnum::BATAL ? "{$status->nama} ({$status->progres})" : $status->nama;
        $createdAt = $status->created_at->translatedFormat('l, d F Y H:i') . ' WITA';
        $updatedAt = $status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA';
        $keterangan = $can_read_keterangan && $status->keterangan ? '<p class="text-muted text-sm mb-0"><i class="fa-solid fa-info-circle me-1"></i>Keterangan: ' . $status->keterangan . '</p>' : '';
        $pegawai = $can_read_pegawai && $status->pegawai_id ? '<p class="text-muted text-sm"><i class="fa-solid fa-user-tie me-1"></i>Pegawai yang memeriksa: ' . $status->pegawai->nama . '</p>' : '';
        $lastUpdated = $status->created_at != $status->updated_at ? '<p class="mb-0 text-sm"><i class="fa-solid fa-rotate me-1"></i>Terakhir diupdate ' . $updatedAt . '</p>' : '';

        $html = <<<HTML
            <div class="d-flex align-items-top gap-2 my-3 icon-status-wraper">
                {$icon}
                <div>
                    <p class="mb-0 {$textColor}">{$namaStatus}</p>
                    <p class="mb-0 text-sm">{$createdAt}</p>
                    {$lastUpdated}
                    {$keterangan}
                    {$pegawai}
                </div>
            </div>
        HTML;
        return $html;
    }
}