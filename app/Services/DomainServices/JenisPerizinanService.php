<?php

namespace App\Services\DomainServices;

use App\Models\JenisPerizinan;

interface JenisPerizinanService {

    function find(int $id): ?JenisPerizinan;

    function findByName(string $name): ?JenisPerizinan;

}