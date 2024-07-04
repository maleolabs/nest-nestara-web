<?php

namespace App\Services\DomainServices;

use App\Models\StatusIzin;

interface StatusIzinService {

    function save(int $izin_id, string $nama_status): ?StatusIzin;

	function find(int $id): ?StatusIzin;

	function update(int $id, array $data_statusizin): ?StatusIzin;

	function delete(int $id): bool;

	function updateStatusIzin(int $izin_id, string $status, string $progres, string $ket = null): void;

}