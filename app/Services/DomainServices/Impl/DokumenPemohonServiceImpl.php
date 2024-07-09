<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\DokumenPemohonService;
use App\Models\DokumenPemohon;

class DokumenPemohonServiceImpl implements DokumenPemohonService {

    public function save(array $data_dokumenpemohon): ?DokumenPemohon
	{
		return DokumenPemohon::create($data_dokumenpemohon);
	}

	public function saveAll(int $pemohon_id, int $izin_id, array $data_persyaratan): void
	{
		foreach ($data_persyaratan as $syarat_id => $nama_file) {
			DokumenPemohon::create([
				'pemohon_id' => $pemohon_id,
				'syarat_izin_id' => $syarat_id,
				'izin_id' => $izin_id,
				'nama_file' => $nama_file
			]);
		}
	}

	public function saveFileAlternates(int $pemohon_id, int $syarat_izin_id, int $izin_id, string $nama_file_alternatif): ?DokumenPemohon
	{
		$dokumen = DokumenPemohon::where('pemohon_id', $pemohon_id)->where('syarat_izin_id', $syarat_izin_id)->where('izin_id', $izin_id)->first();
		if (!$dokumen)
			return null;
		$dokumen->nama_file_alternatif = $nama_file_alternatif;
		$dokumen->save();
		return $dokumen;
	}

	public function find(int $id): ?DokumenPemohon
	{
		return DokumenPemohon::find($id);
	}

	public function findByPemohonAndSyaratAndIzin(int $pemohon_id, int $syarat_izin_id, int $izin_id): ?DokumenPemohon
	{
		return DokumenPemohon::where('pemohon_id', $pemohon_id)->where('syarat_izin_id', $syarat_izin_id)->where('izin_id', $izin_id)->first();
	}

	public function getAllByPemohonAndSyaratAndIzin(int $pemohon_id, int $jenis_perizinan_id, int $izin_id): array
	{
		$semua_dokumen = DokumenPemohon::
			with(['syarat_izin' => function ($syarat_izin) use ($jenis_perizinan_id) {
				$syarat_izin->whereHas('jenis_perizinan', function($jenis_perizinan) use ($jenis_perizinan_id) {
					$jenis_perizinan->where('jenis_perizinan.id', $jenis_perizinan_id);
				});
			}])
			->where('pemohon_id', $pemohon_id)
			->where('izin_id', $izin_id)
			->get();
		$result = [];
		foreach ($semua_dokumen as $dokumen) {
			$result[$dokumen->syarat_izin_id] = $dokumen;
		}
		return $result;
	}

	public function deleteByPemohonAndSyaratIzin(int $pemohon_id, int $syarat_izin_id): bool
	{
		$semua_dokumen = DokumenPemohon::where('pemohon_id', $pemohon_id)->where('syarat_izin_id', $syarat_izin_id)->get();
		foreach ($semua_dokumen as $dokumen) {
			$nama_file = $dokumen->nama_file;
			$nama_file_alternate = $dokumen->nama_file_alternate;
			$dokumen->delete();
			// todo: delete file from storage
		}
		return count($semua_dokumen) > 0;
	}

}