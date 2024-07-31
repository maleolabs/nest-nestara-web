<?php

namespace App\Services\DomainServices;

use App\Models\User;

interface UserService {

    /**
     * Create or update user
     * 
     * 'foto' attributes are not included, it will be saved on a different method
     * 
     * The default user photo in this method will be generated automatically (for create only)
     * 
     * @param int $user_id = null
     * @param array $user_data
     * @param string $role_name = null
     * 
     * @return \App\Models\User 
     */
    function save(int $user_id = null, array $user_data, string $role_name = null): ?User;

    /**
     * find user by id
     * 
     * @param int $user_id
     * 
     * @return \App\Models\User
     */
    function find(int $user_id): ?User;

    /**
     * assign any roles to user
     * 
     * @param int $user_id
     * @param array $role_names
     * 
     * @return void
     */
    function assignRoles(int $user_id, array $role_names): void;

    /**
     * Mengambil semua pengguna berdasarkan role tertentu
     * 
     * @param string $role_name Nama role
     * @return array 
     */
    function getAllByRole(string $role_name): array;

}