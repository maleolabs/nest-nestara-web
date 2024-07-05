<?php

namespace App\Services\DomainServices;

use App\Models\EntriData;

interface EntriDataService {

    function save(array $data_entridata): ?EntriData;

	function find(int $id): ?EntriData;

	function update(int $id, array $data_entridata): ?EntriData;

	function delete(int $id): bool;

	
}