<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\PengaturanService;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\DB;

class PengaturanServiceImpl implements PengaturanService {

	public function createMany(array $pengaturan): void
	{
		DB::table('pengaturan')->insert($pengaturan);
	}

    public function updateOrCreate(string $nama, $value, ?string $group = null, $user_id = null, bool $hidden = false): ?Pengaturan
	{
		$tipe = is_int($value) ? 'int' : (is_bool($value) ? 'boolean' : (is_array($value) ? 'json' : (strtotime($value) !== false ? 'datetime' : 'text')));
		return Pengaturan::updateOrCreate(
			['nama' => $nama, 'user_id' => $user_id, 'group' => $group], 
			['value' => $value, 'tipe' => $tipe, 'hidden' => $hidden]
		);
	}

	public function find(string $nama, $user_id = null): ?Pengaturan
	{
		// kolom pengaturan: user_id (nullable, FK users), bukan id (PK tabel) — WEB-F03.
		// user_id null = baris global; dengan user_id terisi: override per-user dulu,
		// fallback global (orderBy user_id DESC menempatkan NULL di urutan terakhir).
		return Pengaturan::where('nama', $nama)
				->where(function($user) use ($user_id) {
					if ($user_id !== null) {
						$user->where('user_id', $user_id)
							->orWhereNull('user_id');
					} else {
						$user->whereNull('user_id');
					}
				})
				->orderBy('user_id', 'DESC')
				->first();
	}

	public function findByNamaAndGroup(string $nama, string $group): ?Pengaturan
	{
		return Pengaturan::where('nama', $nama)
				->where('group', $group)
				->first();
	}

	public function getAllGroups(): array
	{
		$all_pengaturan = Pengaturan::all()->groupBy('group');
		return $all_pengaturan->all();
	}

	public function getAllByGroup(string $group): array
	{
		$all_by_group = Pengaturan::where('group', $group)->get();
		return $all_by_group->all();
	}

	public function delete(int $id): bool
	{
		$pengaturan = Pengaturan::find($id);
		if (!$pengaturan) return false;
		return $pengaturan->delete();
	}

	public function deleteByNama(string $nama): ?bool
	{
		return Pengaturan::where('nama', $nama)->first()?->delete();
	}
	
}