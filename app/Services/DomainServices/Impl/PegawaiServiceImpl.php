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

	public function saveField(int $pegawai_id, string $field, $value): void
	{
		$pegawai = Pegawai::find($pegawai_id);
		if (!$pegawai) return;


		$pegawai->$field = $value;
		$pegawai->save();
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

	public function dismissPenandangan(int $id): void
	{
		$peran_pegawai = PeranPegawai::where('pegawai_id', $id)->where('peran', 'penandatangan')->first();
		if (!$peran_pegawai) return;

		$peran_pegawai->delete();
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