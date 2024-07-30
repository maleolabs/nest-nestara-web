<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\EntriDataService;
use App\Models\EntriData;
use App\Models\EntriDataIzin;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EntriDataServiceImpl implements EntriDataService {

    public function save(int $jenis_perizinan_id, array $data_entridata): ?EntriData
	{
		$data_entridata['jenis_perizinan_id'] = $jenis_perizinan_id;
		$entri_data = EntriData::create($data_entridata);

		return $entri_data;
	}

	public function saveFieldById(int $id, string $field, string $value): void
	{
		$entri = EntriData::find($id);
		if (!$entri) return;

		$entri->$field = $value;
		$entri->save();
	}

	public function find(int $id): ?EntriData
	{
		return EntriData::find($id);
	}

	public function findFirstEntriIzinByDataId(int $data_id, int $izin_id): ?EntriDataIzin
	{
		$entri_data_izin = EntriDataIzin::where('entri_data_id', $data_id)->where('izin_id', $izin_id)->first();
		if (!$entri_data_izin)
			$entri_data_izin = EntriDataIzin::whereHas('entri_data', function ($entri_data) use ($data_id) {
				$entri_data->where('id', $data_id)->withTrashed();
			})->where('izin_id', $izin_id)->first();
		return $entri_data_izin;
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

	public function getByJenisPerizinanIdWithOldData(int $jenis_perizinan_id, int $izin_id): array
	{
		$izin = $this->getPerizinanService()->find($izin_id);
		if (!$izin)
			return [];
		$entri_data = EntriData::where('jenis_perizinan_id', $jenis_perizinan_id)->withTrashed()->get();
		return $entri_data->all();
	}

	public function getDataIzinGroupByNama(int $izin_id, int $jenis_perizinan_id): array
	{
		$data_izin = EntriData::leftJoin('entri_data_izin', 'entri_data.id', '=', 'entri_data_izin.entri_data_id')
			->where('entri_data.jenis_perizinan_id', $jenis_perizinan_id)
			->where(function($query) use ($izin_id) {
				$query->whereIn('entri_data.id', function ($sub_query) use ($izin_id) {
					$sub_query->select('entri_data_izin.entri_data_id')
						->from('entri_data_izin')
						->where('entri_data_izin.izin_id', $izin_id);
				})->orWhereNotIn('entri_data.id', function ($sub_query) use ($izin_id) {
					$sub_query->select('entri_data_izin.entri_data_id')
						->from('entri_data_izin')
						->where('entri_data_izin.izin_id', $izin_id);
				});
			})
			->pluck('entri_data_izin.value', 'entri_data.nama');
		$data_izin = $data_izin->all();

		foreach ($data_izin as $data_nama => $data_value) {
			if (str_contains($data_value, ';')) {
				$value = explode(';', $data_value);
				$data_izin[$data_nama] = $value;
			}
			if ($data_value === null)
				$data_izin[$data_nama] = '-';
			else {
				try {
					$date_value = Carbon::parse($data_value)->translatedFormat('d F Y');
					$data_izin[$data_nama] = $date_value;
				} catch (InvalidFormatException $e) {
					continue;
				}
			}
		}

		return $data_izin;
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

	public function isEntriDataIzinExistsByIzinAndJenis(int $izin_id, int $jenis_perizinan_id): bool
	{
		$entri_data = EntriData::select('id')->where('jenis_perizinan_id', $jenis_perizinan_id)->where('mandatori', true)->get();
		$entri_data_izin = EntriDataIzin::select('entri_data_id')->where('izin_id', $izin_id)->get();
		return (count($entri_data_izin) >= count($entri_data)) && ($entri_data->pluck('id') == $entri_data_izin->pluck('entri_data_id'));
	}

	public function getOldEntriDataByIzin(\App\Models\Izin $izin): array
	{
		$data_old = [];
		$data_with_deletes_entri = $this->getTrashedEntriData($izin->id);
		
		if (count($data_with_deletes_entri) == 0)
			return $data_old;
		$plucked_data_with_deletes_entri = $data_with_deletes_entri->mapWithKeys(function ($data) {
			return [$data->entri_data->nama => $data->value];
		});
		$entri_data = EntriData::select('id', 'nama')->where('jenis_perizinan_id', $izin->jenis_perizinan_id)->get();
		foreach ($entri_data as $entri) {
			if (isset($plucked_data_with_deletes_entri[$entri->nama]))
				$data_old[$entri->id] = $plucked_data_with_deletes_entri[$entri->nama];
		}
		return $data_old;
	}

	public function hasTrashedEntriDataIzin(int $izin_id): bool
	{
		return count($this->getTrashedOnlyEntriData($izin_id)) > 0;
	}

	private function getTrashedOnlyEntriData(int $izin_id): Collection
	{
		return EntriDataIzin::where('izin_id', $izin_id)->whereHas('entri_data', function($entri_data_query) {
					$entri_data_query->onlyTrashed();
				})->get();
	}

	private function getTrashedEntriData(int $izin_id): Collection
	{
		return EntriDataIzin::where('izin_id', $izin_id)->with('entri_data', function($entri_data_query) {
					$entri_data_query->withTrashed();
				})->get();
	}

	public function getDeletedAtByJenis(int $jenis_perizinan_id): ?string
	{
		$trashed_entri = EntriData::onlyTrashed()
							->where('jenis_perizinan_id', $jenis_perizinan_id)
							->first();
		return $trashed_entri->deleted_at ?? null;
	}

	public function deleteOldEntriData(int $jenis_perizinan_id): void
	{
		DB::transaction(function () use ($jenis_perizinan_id) {
			$old_entri_data = EntriData::onlyTrashed()
									->where('jenis_perizinan_id', $jenis_perizinan_id);
			EntriDataIzin::whereIn('entri_data_id', $old_entri_data->pluck('id'))->delete();
		});
	}

	private function getPerizinanService(): \App\Services\DomainServices\PerizinanService
	{
		return app(\App\Services\DomainServices\PerizinanService::class);
	}
	
}