<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\PengaturanService;
use App\Models\Pengaturan;

class PengaturanServiceImpl implements PengaturanService {

    public function updateOrCreate(string $nama, $value, ?string $group = null, $user_id = null): ?Pengaturan
	{
		$tipe = is_int($value) ? 'int' : (is_bool($value) ? 'boolean' : (strtotime($value) === false ? 'text' : 'datetime'));
		return Pengaturan::updateOrCreate(
			['nama' => $nama, 'user_id' => $user_id, 'group' => $group], 
			['value' => $value, 'tipe' => $tipe, 'group' => $group]);
	}

	public function find(string $nama, $user_id = null): ?Pengaturan
	{
		return Pengaturan::where('nama', $nama)
				->where(function($user) use ($user_id) {
					$user->where('id', $user_id)
						->orWHereNull('user_id');
				})
				->orderBy('user_id', 'DESC')
				->first();
	}

	public function getAllGroups(): array
	{
		$all_pengaturan = Pengaturan::all()->groupBy('group');
		return $all_pengaturan->all();
	}

	public function delete(int $id): bool
	{
		$pengaturan = Pengaturan::find($id);
		if (!$pengaturan) return false;
		return $pengaturan->delete();
	}

	
}