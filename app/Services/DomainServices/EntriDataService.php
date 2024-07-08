<?php

namespace App\Services\DomainServices;

use App\Models\EntriData;

interface EntriDataService {

    function save(array $data_entridata): ?EntriData;

	function find(int $id): ?EntriData;

	function update(int $id, array $data_entridata): ?EntriData;

	function delete(int $id): bool;

	function getByJenisPerizinanId(int $jenis_perizinan_id): array;

	/**
	 * save one or many data entri izin
	 * 
	 * @param mixed $entri_data_izin
	 * 
	 * @param int $izin_id
	 * 
	 * @param string|null $value
	 * 
	 * @return array
	 */
	function saveEntriDataIzin($entri_data_izin, int $izin_id, string $value = null): ?array;

	function isEntriDataIzinExistsByIzinAndJenis(int $izin_id, int $jenis_perizinan_id): bool;

	/**
	 * retrieve old data that has been soft deleted
	 */
	function getOldEntriDataByIzin(\App\Models\Izin $izin): array;

	function hasTrashedEntriDataIzin(int $izin_id): bool;

	function getDeletedAtByJenis(int $jenis_perizinan_id): ?string;

	function deleteOldEntriData(int $jenis_perizinan_id): void;

}