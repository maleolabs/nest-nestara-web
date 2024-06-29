<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Services\DomainServices\PerizinanService;

class PerizinanServiceImpl implements PerizinanService
{
    
    public function save(int $jenis_izin_id, int $pemohon_id): ?Izin
    {
        return Izin::create([
            'jenis_perizinan_id' => $jenis_izin_id,
            'pemohon_id' => $pemohon_id
        ]);
    }

    public function find(int $id): ?Izin
    {
        return Izin::find($id);
    }
    
}