<?php

namespace App\Services\DomainServices\Impl;

use App\Models\JenisPerizinan;
use App\Services\DomainServices\SyaratIzinService;
use App\Models\SyaratIzin;
use Illuminate\Support\ItemNotFoundException;

class SyaratIzinServiceImpl implements SyaratIzinService
{

    public function save(array $data_syaratizin): ?SyaratIzin
	{
		return null;
	}

	public function find(int $id): ?SyaratIzin
	{
		return null;
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
	
}