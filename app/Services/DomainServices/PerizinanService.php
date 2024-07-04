<?php

namespace App\Services\DomainServices;

use App\Models\Izin;

interface PerizinanService
{
    
    function save(int $jenis_izin_id, int $pemohon_id): ?Izin;

    function find(int $id): ?Izin;

    function getAll($search = '', $filter_status, $sort = 'DESC'): array;

    function saveRegistrationAction(int $izin_id, string $tanggal_peninjauan, string $lokasi_izin, bool $selesai = false): bool;
    
}