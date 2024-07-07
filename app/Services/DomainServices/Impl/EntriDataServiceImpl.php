<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\EntriDataService;
use App\Models\EntriData;
use App\Models\EntriDataIzin;

class EntriDataServiceImpl implements EntriDataService {

    public function save(array $data_entridata): ?EntriData
	{
		return null;
	}

	public function find(int $id): ?EntriData
	{
		return null;
	}

	public function update(int $id, array $data_entridata): ?EntriData
	{
		return null;
	}

	public function delete(int $id): bool
	{
		return false;
	}

	public function getByJenisPerizinanId(int $jenis_perizinan_id): array
	{
		$entri_data = EntriData::where('jenis_perizinan_id', $jenis_perizinan_id)->get();
		return $entri_data->all();
	}

	public function saveEntriDataIzin($entri_data, int $izin_id, string $value = null): array
	{
		$entri_data_izin = null;
		if (is_array($entri_data)) {
			foreach ($entri_data as $entri_data_izin_id => $entri_value) {
				$entri_data_izin[] = $this->updateOrCreateEntriDataIzin($entri_data_izin_id, $izin_id, $entri_value);
			}
			$entri_data_izin = EntriData::whereIn('id', array_keys($entri_data))->with(['entri_data_izin'])->get();
		} else {
			$this->updateOrCreateEntriDataIzin($entri_data, $izin_id, $value);
			$entri_data_izin = EntriData::where('id', $entri_data)->get();
		}
		return $entri_data_izin->all();
	}

	private function updateOrCreateEntriDataIzin(int $entri_data_izin_id, int $izin_id, string $value): void
	{
		EntriDataIzin::updateOrCreate(
			[
				'entri_data_id' => $entri_data_izin_id,
				'izin_id' => $izin_id
			],
			[
				'value' => $value,
				'updated_at' => now()
			]
		);
	}
	
}