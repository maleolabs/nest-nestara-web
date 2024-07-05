<?php

namespace App\Services\DomainServices;

use Spatie\Permission\Models\Role;

interface RoleService {

    /**
     * save new role
     * 
     * @param string $name
     * @param string $gurd_name
     * 
     * @return null|\Spatie\Permission\Models\Role
     */
    function save(string $name, string $guard_name = 'web'): ?Role;

    /**
     * find role by its name
     * 
     * @param string $name
     * 
     * @return null|\Spatie\Permission\Models\Role
     */
    function findByName(string $name): ?Role;

    /**
     * get all roles by names
     * 
     * @param array $names
     * 
     * @return array
     */
    function getAllByNames(array $names, bool $only_names = false): array;

}