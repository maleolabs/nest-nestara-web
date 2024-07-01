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

}