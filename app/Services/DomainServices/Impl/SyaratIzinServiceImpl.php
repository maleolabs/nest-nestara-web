<?php

namespace App\Services\DomainServices\Impl;

use App\Models\JenisPerizinan;
use App\Services\DomainServices\SyaratIzinService;
use App\Models\SyaratIzin;

class SyaratIzinServiceImpl implements SyaratIzinService
{

	public function saveField(int $syarat_id, string $field, $value): void
	{
		$syarat = $this->find($syarat_id);
		if (!$syarat) return;

		$syarat->update([$field => $value]);
	}

	public function find(int $id): ?SyaratIzin
	{
		return SyaratIzin::find($id);
	}

	public function delete(int $id): bool
	{
		return false;
	}

	public function assignSyaratToJenisByName(string $nama_syarat, string $nama_jenis): void
	{
		$syarat_izin = SyaratIzin::where('nama', $nama_syarat)->first();
		if (!$syarat_izin)
			return;
		$jenis_izin = JenisPerizinan::where('nama', $nama_jenis)->first();
		if (!$jenis_izin)
			return;
		$syarat_izin->jenis_perizinan()->attach($jenis_izin);
	}

	public function assignAllSyaratToJenisByName(array $syarat, int $id_jenis): void
	{
		foreach ($syarat as $id => $nama_syarat) {
			if (str_starts_with($id, '0') && is_string($id)) {
				$new_syarat = SyaratIzin::create([
					'nama' => $nama_syarat,
					'tipe' => 'img'
				]);
				unset($syarat[$id]);
				$syarat[$new_syarat->id] = $nama_syarat;
			}
		}

		$jenis_izin = JenisPerizinan::find($id_jenis);
		if (!$jenis_izin) return;

		$jenis_izin->syarat_izin()->attach(array_keys($syarat));
	}

	public function getByJenisAndPemohon(int $jenis_izin_id, int $pemohon_id): array
	{
		$persyaratan = SyaratIzin::join('jenis_perizinan_syarat_izin', 'syarat_izin.id', '=', 'jenis_perizinan_syarat_izin.syarat_izin_id')
		->where('jenis_perizinan_syarat_izin.jenis_perizinan_id', $jenis_izin_id)
		->select('syarat_izin.*')
		->with(['dokumen_pemohon' => function($dokumen_pemohon) use ($pemohon_id) {
			$dokumen_pemohon->where('pemohon_id', $pemohon_id);
		}])
		->get();
		$result = [];
		foreach ($persyaratan as $syarat) {
			$result[$syarat->id] = $syarat;
		}
		return $result;
	}

	public function getIdsTypePdfByJenisAndPemohon(int $jenis_izin_id, int $pemohon_id): array
	{
		$persyaratan = SyaratIzin::join('jenis_perizinan_syarat_izin', 'syarat_izin.id', '=', 'jenis_perizinan_syarat_izin.syarat_izin_id')
		->where('jenis_perizinan_syarat_izin.jenis_perizinan_id', $jenis_izin_id)
		->select('syarat_izin.*')
		->where('syarat_izin.tipe', 'pdf')
		->with(['dokumen_pemohon' => function($dokumen_pemohon) use ($pemohon_id) {
			$dokumen_pemohon->where('pemohon_id', $pemohon_id);
		}])
		->pluck('syarat_izin.id');
		return $persyaratan->all();
	}

	public function getAllWhereNotInJenisPerizinan(int $jenis_izin_id, string $search = '', int $limit = 10): array
	{
		$syarat = SyaratIzin::join('jenis_perizinan_syarat_izin', 'syarat_izin.id', '=', 'jenis_perizinan_syarat_izin.syarat_izin_id')
					->whereNotIn('jenis_perizinan_syarat_izin.jenis_perizinan_id', [$jenis_izin_id])
					->where('syarat_izin.nama', 'like', '%' . $search . '%')
					->select('syarat_izin.id', 'syarat_izin.nama')
					->distinct()
					->take($limit)
					->pluck('syarat_izin.nama', 'syarat_izin.id');

		return $syarat->all();
	}
	
}