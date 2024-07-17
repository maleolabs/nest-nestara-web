<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\PegawaiService;
use App\Models\Pegawai;
use App\Models\PeranPegawai;

class PegawaiServiceImpl implements PegawaiService {

    public function save(array $data_pegawai, ?string $peran_pegawai = null): ?Pegawai
	{
		$pegawai = Pegawai::create($data_pegawai);
		if ($peran_pegawai && in_array($peran_pegawai, ['penandatangan', 'timpemeriksa']))
			PeranPegawai::create([
				'pegawai_id' => $pegawai->id,
				'peran' => $peran_pegawai
			]);
		return $pegawai;
	}

	public function find(int $id): ?Pegawai
	{
		return Pegawai::find($id);
	}

	public function getAllPenandaTangan(): array
	{
		$penandatangan = Pegawai::whereHas('peran_pegawai', function ($query) {
			$query->where('peran', 'penandatangan');
		})->get();
		return $penandatangan->all();
	}

	public function update(int $id, array $data_pegawai): ?Pegawai
	{
		$pegawai = $this->find($id);
		if (!$pegawai)
			return null;
		$pegawai->update($data_pegawai);
		return $pegawai;
	}

	public function delete(int $id): bool
	{
		$pegawai = $this->find($id);
		if (!$pegawai)
			return false;
		return $pegawai->delete();
	}
	
}