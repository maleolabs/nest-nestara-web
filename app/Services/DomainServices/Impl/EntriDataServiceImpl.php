<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\EntriDataService;
use App\Models\EntriData;

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

	
}