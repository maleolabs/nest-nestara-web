<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\RoleService;
use Spatie\Permission\Models\Role;

class RoleServiceImpl implements RoleService {

    public function save(string $name, string $guard_name = 'web'): ?Role
    {
        return Role::create([
            'name' => $name,
            'guard_name' => $guard_name
        ]);
    }

    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    public function getAllByNames(array $names, bool $only_names = false): array
    {
        $roles = Role::whereIn('name', $names);
        $roles = $only_names ? $roles->pluck('name') : $roles->get();
        return $roles->all();
    }

}