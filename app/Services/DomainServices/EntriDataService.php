<?php

namespace App\Services\DomainServices;

use App\Models\EntriData;
use App\Models\EntriDataIzin;

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

}