<?php

namespace App\Services\DomainServices;

use App\Models\JenisPerizinan;

interface JenisPerizinanService {

    function find(int $id): ?JenisPerizinan;

    function findByName(string $name): ?JenisPerizinan;

    function getPersyaratanByJenisAndPemohon(int $jenis_id, int $pemohon_id): array;

}