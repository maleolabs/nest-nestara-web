<?php

namespace App\Services\DomainServices;

use App\Models\DokumenPemohon;

interface DokumenPemohonService {

    function save(array $data_dokumenpemohon): ?DokumenPemohon;

	function saveAll(int $pemohon_id, array $data_persyaratan): void;

	function find(int $id): ?DokumenPemohon;

	function update(int $id, array $data_dokumenpemohon): ?DokumenPemohon;

	function delete(int $id): bool;
	
}