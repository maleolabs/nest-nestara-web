<?php

namespace App\Services\DomainServices\Impl;

use App\Models\JenisPerizinan;
use App\Services\DomainServices\SyaratIzinService;
use App\Models\SyaratIzin;

class SyaratIzinServiceImpl implements SyaratIzinService
{

    public function save(array $data_syaratizin): ?SyaratIzin
	{
		return null;
	}

	public function find(int $id): ?SyaratIzin
	{
		return SyaratIzin::find($id);
	}

	public function update(int $id, array $data_syaratizin): ?SyaratIzin
	{
		return null;
	}

	public function delete(int $id): bool
	{
		return false;
	}

	public function assignSyaratToJenisByName(string $nama_syarat, string $nama_jenis): void
	{
		$syarat_izin = SyaratIzin::where('nama', $nama_syarat)->first();
		if (!$syarat_izin)
			// throw new ItemNotFoundException('syarat izin tidak ditemukan');
			return; // skip if data not found
		$jenis_izin = JenisPerizinan::where('nama', $nama_jenis)->first();
		if (!$jenis_izin)
			// throw new ItemNotFoundException('jenis perizinan tidak ditemukan: ' . $nama_jenis);
			return; // skip if data not found
		$syarat_izin->jenis_perizinan()->attach($jenis_izin);
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
	
}