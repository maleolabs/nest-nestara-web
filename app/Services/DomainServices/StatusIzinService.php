<?php

namespace App\Services\DomainServices;

use App\Models\StatusIzin;

interface StatusIzinService {

    function save(int $izin_id, string $nama_status): ?StatusIzin;

	function find(int $id): ?StatusIzin;

	function update(int $id, array $data_statusizin): ?StatusIzin;

	function delete(int $id): bool;

	function saveAllStatusIzin(array $status_izin_data): void;

	function updateStatusIzin(int $izin_id, string $status, string $progres, string $ket = null, int $pegawai_id = null, $updated_at = null): void;

}