<?php

namespace App\Services\DomainServices;

use App\Models\Pegawai;

interface PegawaiService {
	/**
     * Menyimpan data pegawai.
     *
     * @param array $data_pegawai Data pegawai yang akan disimpan.
     * @param string|null $peran_pegawai Peran pegawai yang akan disimpan, opsional.
     * @return Pegawai|null Mengembalikan objek Pegawai jika berhasil disimpan, atau null jika gagal.
     */
    function save(array $data_pegawai, ?string $peran_pegawai = null): ?Pegawai;

    /**
     * Menyimpan data pegawai untuk field atau kolom tertentu
     * 
     * @param int $pegawai_id ID pegawai
     * @param string $field Nama field atau kolom yang akan diperbaharui
     * @param mixed $value Value dari field
     * 
     * @return void Tidak mengembalikan apa-apa
     */
    function saveField(int $pegawai_id, string $field, $value): void;

    /**
     * Mencari pegawai berdasarkan ID.
     *
     * @param int $id ID pegawai yang akan dicari.
     * @return Pegawai|null Mengembalikan objek Pegawai jika ditemukan, atau null jika tidak ditemukan.
     */
    function find(int $id): ?Pegawai;

    /**
     * Mengambil semua pegawai yang bertindak sebagai penanda tangan.
     *
     * @return array Mengembalikan array yang berisi semua objek Pegawai yang bertindak sebagai penanda tangan.
     */
    function getAllPenandaTangan(): array;

    /**
     * Mengabaikan pegawai sebagai penandatangan
     * 
     * @param int $id ID dari pegawai
     * @return void
     */
    function dismissPenandangan(int $id): void;

    /**
     * Memperbarui data pegawai berdasarkan ID.
     *
     * @param int $id ID pegawai yang akan diperbarui.
     * @param array $data_pegawai Data pegawai yang baru.
     * @return Pegawai|null Mengembalikan objek Pegawai yang telah diperbarui jika berhasil, atau null jika gagal.
     */
    function update(int $id, array $data_pegawai): ?Pegawai;

    /**
     * Menghapus pegawai berdasarkan ID.
     *
     * @param int $id ID pegawai yang akan dihapus.
     * @return bool Mengembalikan true jika penghapusan berhasil, atau false jika gagal.
     */
    function delete(int $id): bool;

}