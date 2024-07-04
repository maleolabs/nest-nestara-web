<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\StatusIzinService;
use App\Models\StatusIzin;
use App\utils\enums\StatusIzinProgressEnum;

class StatusIzinServiceImpl implements StatusIzinService {

    public function save(int $izin_id, string $nama_status): ?StatusIzin
	{
		return StatusIzin::create([
			'izin_id' => $izin_id,
			'nama' => $nama_status,
			'progres' => StatusIzinProgressEnum::FINISHED
		]);
	}

	public function find(int $id): ?StatusIzin
	{
		return null;
	}

	public function update(int $id, array $data_statusizin): ?StatusIzin
	{
		return null;
	}

	public function delete(int $id): bool
	{
		return false;
	}

	public function updateStatusIzin(int $izin_id, string $status, string $progres, string $ket = null): void
	{
		$status_izin = StatusIzin::where('izin_id', $izin_id)->where('nama', $status)->first();
		if ($status_izin) {
			$status_izin->progres = $progres;
			$status_izin->keterangan = $ket;
			$status_izin->save();
		} else {
			StatusIzin::create([
				'izin_id' => $izin_id,
				'nama' => $status,
				'progres' => $progres,
				'keterangan' => $ket
			]);
		}
	}

}