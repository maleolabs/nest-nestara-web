<?php

namespace App\Services\DomainServices;

use App\Models\Pengaturan;

interface PengaturanService {

    /**
     * Membuat banyak pengaturan sekaligus.
     *
     * @param array $pengaturan Array asosiasi dengan nama pengaturan sebagai kunci dan nilai pengaturan sebagai nilai.
     * @return void
     */
    function createMany(array $pengaturan): void;

    /**
     * Membuat atau memperbarui pengaturan berdasarkan nama.
     *
     * @param string $nama Nama pengaturan.
     * @param mixed $value Nilai pengaturan.
     * @param string|null $group (Opsional) Kelompok pengaturan.
     * @param mixed|null $user_id (Opsional) ID pengguna terkait.
     * @patam bool $hidden (Opsional) Apakah pengaturan harus disembunyikan.
     * @return Pengaturan|null Objek Pengaturan yang diperbarui atau dibuat, atau null jika gagal.
     */
    function updateOrCreate(string $nama, $value, string $group = null, $user_id = null, bool $hidden = false): ?Pengaturan;

    /**
     * Menemukan pengaturan berdasarkan nama.
     *
     * @param string $nama Nama pengaturan.
     * @param mixed|null $default Nilai default jika pengaturan tidak ditemukan.
     * @param mixed|null $user_id (Opsional) ID pengguna terkait.
     * @return Pengaturan|null Objek Pengaturan yang ditemukan, atau null jika tidak ditemukan.
     */
    function find(string $nama, $user_id = null): ?Pengaturan;

    /**
     * Mencari objek Pengaturan berdasarkan nama dan group
     *
     * @param string $nama Nama Pengaturan yang akan dicari.
     * @param string $group Group Pengaturan yang akan dicari.
     * @return Pengaturan|null Objek Pengaturan jika ditemukan, jika tidak null.
     */
    function findByNamaAndGroup(string $nama, string $group): ?Pengaturan;

    /**
     * Mendapatkan semua kelompok pengaturan.
     *
     * @return array Daftar semua kelompok pengaturan.
     */
    function getAllGroups(): array;

    /**
     * Mendapatkan semua pengaturan berdasarkan kelompok.
     *
     * @param string $group Kelompok pengaturan yang akan diambil.
     * @return array Daftar semua pengaturan dalam kelompok yang ditentukan.
     */
    function getAllByGroup(string $group): array;

    /**
     * Menghapus pengaturan berdasarkan ID.
     *
     * @param int $id ID pengaturan.
     * @return bool True jika pengaturan berhasil dihapus, false jika gagal.
     */
    function delete(int $id): bool;

    /**
     * Menghapus pengaturan berdasarkan nama
     * 
     * @param string $nama Nama pengaturan
     * @return bool|null True jika pengaturan berhasil dihapus, false jika gagal.
     */
    function deleteByNama(string $nama): ?bool;
	
}