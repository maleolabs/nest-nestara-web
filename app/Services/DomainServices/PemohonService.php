<?php

namespace App\Services\DomainServices;

use App\Models\Pemohon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PemohonService
{

    function save(array $data_pemohon, ?int $user_id = null): ?Pemohon;

    /**
     * Mencari pemohon yatim (belum terhubung ke akun user manapun)
     * berdasarkan nomor identitas.
     *
     * Dipakai pada alur create API: kalau user mendaftarkan nomor_id yang
     * sebelumnya dimiliki akun yang sudah dihapus, pemohon lama diadopsi
     * agar riwayat izinnya tetap menyatu.
     */
    function findOrphanByNomorId(string $nomor_id): ?Pemohon;

    /**
     * Mengadopsi pemohon yatim: mengaitkannya ke akun user tertentu
     * lalu memperbarui seluruh datanya dengan input terbaru.
     */
    function adopt(int $id, int $user_id, array $data_pemohon): ?Pemohon;

    function update(int $id, array $data_pemohon): ?Pemohon;

    function find(int $id): ?Pemohon;

    function findByNomorIdOrNamaPemohon(string $cari): ?Pemohon;

    function getAll($search = '', ?int $limit = null): array;

    function getPaginated(string $search = '', int $per_page = 20, int $page = 1, string $page_name = 'page'): LengthAwarePaginator;

    function getByNomorIdOrNamaPemohon(string $cari): array;

    function delete(int $id): bool;

}