<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\DokumenPemohonService;
use App\Models\DokumenPemohon;

class DokumenPemohonServiceImpl implements DokumenPemohonService {

    public function save(array $data_dokumenpemohon): ?DokumenPemohon
	{
		return DokumenPemohon::create($data_dokumenpemohon);
	}

	public function saveAll(int $pemohon_id, array $data_persyaratan): void
	{
		foreach ($data_persyaratan as $syarat_id => $nama_file) {
			DokumenPemohon::create([
				'pemohon_id' => $pemohon_id,
				'syarat_izin_id' => $syarat_id,
				'nama_file' => $nama_file
			]);
		}
	}

	public function find(int $id): ?DokumenPemohon
	{
		return null;
	}

	public function update(int $id, array $data_dokumenpemohon): ?DokumenPemohon
	{
		return null;
	}

	public function delete(int $id): bool
	{
		return false;
	}

}