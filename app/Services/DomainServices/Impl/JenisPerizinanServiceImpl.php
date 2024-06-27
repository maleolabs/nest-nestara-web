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

}