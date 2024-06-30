<?php

namespace App\Services\DomainServices;

use App\Models\Pemohon;

interface PemohonService
{
    
    function save(array $data_pemohon, int $user_id = null): ?Pemohon;

    function update(int $id, array $data_pemohon): ?Pemohon;

    function find(int $id): ?Pemohon;

    function findByNomorIdOrNamaPemohon(string $cari): ?Pemohon;

    function getAll($search = ''): array;

    function getByNomorIdOrNamaPemohon(string $cari): array;

    function delete(int $id): bool;

}