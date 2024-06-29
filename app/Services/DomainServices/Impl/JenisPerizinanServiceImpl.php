<?php

namespace App\Services\DomainServices\Impl;

use App\Models\JenisPerizinan;
use App\Services\DomainServices\JenisPerizinanService;

class JenisPerizinanServiceImpl implements JenisPerizinanService {

    public function find(int $id): ?JenisPerizinan
    {
        return JenisPerizinan::with('syarat_izin')->where('id', $id)->first();
    }

    public function findByName(string $name): ?JenisPerizinan
    {
        return JenisPerizinan::with('syarat_izin')->where('nama', $name)->first();
    }

    public function getPersyaratanByJenisAndPemohon(int $jenis_id, int $pemohon_id): array
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan)
            return [];
        return $perizinan->syarat_izin()->with('dokumen_pemohon', function ($dokumen_pemohon) use ($pemohon_id) {
            $dokumen_pemohon->where('pemohon_id', $pemohon_id);
        })->get();
    }

}