<?php

namespace App\Services\DomainServices;

use App\Models\Pegawai;

interface PegawaiService {

    function save(array $data_pegawai): ?Pegawai;

	function find(int $id): ?Pegawai;

	function update(int $id, array $data_pegawai): ?Pegawai;

	function delete(int $id): bool;

	
}