<?php

namespace App\Services\DomainServices;

use App\Models\StatusIzin;

interface StatusIzinService {

    function save(int $izin_id, string $nama_status): ?StatusIzin;

	function findByNamaAndIzin(string $nama, int $izin_id): ?StatusIzin;

	function saveAllStatusIzin(array $status_izin_data): void;

	function updateStatusIzin(int $izin_id, string $status, string $progres, string $ket = null, int $pegawai_id = null, $updated_at = null, bool $new = false): void;

	function deleteAllByIzinId(int $izin_id): void;

}