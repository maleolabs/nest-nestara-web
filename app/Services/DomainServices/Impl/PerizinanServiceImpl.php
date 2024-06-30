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

    public function getAll($search = ''): array
    {
        $semua_izin = Izin::where(function ($query) use ($search) {
            $query->whereHas('jenis_perizinan', function($jenis_perizinan) use ($search) {
                $jenis_perizinan->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);
            })->orWhereHas('pemohon', function ($pemohon) use ($search) {
                $pemohon->whereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('nomor_id', 'LIKE', '%' . $search . '%');
            });
        })->get();
        return $semua_izin->all();
    }
    
}