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
     * @param ?int $user_id = null
     * @param array $user_data
     * @param ?string $role_name = null
     * 
     * @return \App\Models\User 
     */
    function save(?int $user_id = null, array $user_data, ?string $role_name = null): ?User;

    /**
     * Menyimpan field atau kolom dari user tertentu
     * 
     * @param int $user_id ID dari user
     * @param string $field Field atau kolom yang akan disimpan
     * @param mixed $value Value dari field atau kolom yang akan disimpan
     * 
     * @return \App\Models\User|null Model User atau null jika gagal disimpan
     */
    function saveField(int $user_id, string $field, $value): ?User;

    /**
     * Menyimpan password untuk user tertentu
     * 
     * @param int $user_id ID dari user
     * @param string $password Password
     * 
     * @return void
     */
    function savePassword(int $user_id, string $password): void;

    /**
     * Menyimpan foto dari komponen Livewire
     * 
     * @param int $user_id ID dari user
     * @param string $original_path Path original dari file
     */
    function saveFotoFromLivewire(int $user_id, string $original_path): void;

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
     * Mengambil semua pengguna
     * 
     * @return arrray Semua pengguna
     */
    function getAll(): array;

    /**
     * Mengambil semua pengguna berdasarkan role tertentu
     * 
     * @param string $role_name Nama role
     * @return array 
     */
    function getAllByRole(string $role_name): array;

    /**
     * Menghapus foto dari user dan menggantinya dengan foto default berdasarkan ID user tertentu
     * 
     * @param int $user_id ID dari user
     */
    function deleteFoto(int $user_id): void;

}