<?php

namespace App\Services\DomainServices;

use App\Models\SyaratIzin;

interface SyaratIzinService 
{

    function save(array $data_syaratizin): ?SyaratIzin;

	function find(int $id): ?SyaratIzin;

	function update(int $id, array $data_syaratizin): ?SyaratIzin;

	function delete(int $id): bool;

	function assignSyaratToJenisByName(string $nama_syarat, string $nama_jenis): void;

	function getByJenisAndPemohon(int $jenis_izin_id, int $pemohon_id): array;
	
}