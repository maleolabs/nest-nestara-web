<?php

namespace App\Services\DomainServices;

use App\Models\Izin;

interface PerizinanService
{
    
    function save(int $jenis_izin_id, int $pemohon_id): ?Izin;
    
}