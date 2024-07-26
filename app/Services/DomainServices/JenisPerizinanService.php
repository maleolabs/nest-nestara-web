<?php

namespace App\Services\DomainServices;

use App\Models\JenisPerizinan;
use Illuminate\Pagination\LengthAwarePaginator;

interface JenisPerizinanService {

    /**
     * Menyimpan catatan JenisPerizinan baru dengan data yang diberikan.
     *
     * @param array $data Sebuah array asosiatif yang berisi data untuk disimpan untuk catatan JenisPerizinan baru.
     * @return JenisPerizinan|null Catatan JenisPerizinan yang baru dibuat, atau null jika operasi penyimpanan gagal.
     */
    function save(array $data): ?JenisPerizinan;

    /**
     * Mencari data jenis perizinan berdasarkan id
     * @param int $id
     * @return JenisPerizinan|null
     */
    function find(int $id): ?JenisPerizinan;

    /**
     * Mencari data jenis perizinan berdasarkan nama
     * @param string $name
     * @return JenisPerizinan|null
     */
    function findByName(string $name): ?JenisPerizinan;

    /**
     * Mendapatkan template PDF untuk jenis perizinan tertentu.
     *
     * @param int $id ID dari jenis perizinan.
     * @return string|null Jalur template PDF untuk jenis perizinan yang diberikan atau null jika template tidak ditemukan.
     */
    function getPdfTemplate(int $id): ?string;

    /**
     * Mengambil semua catatan jenis perizinan, secara opsional diurutkan berdasarkan kolom dan arah tertentu.
     *
     * @param string $sorting_by Kolom untuk mengurutkan hasil. Default ke 'created_at'.
     * @param string $sort Arah pengurutan, baik 'ASC' atau 'DESC'. Default ke 'DESC'.
     * @return array Sebuah array dari model JenisPerizinan.
     */
    function getAll(string $sorting_by = 'created_at', string $sort = 'DESC'): array;

    /**
     * Mengambil semua jenis perizinan dengan paginate
     * 
     * @param int $page Halaman yang akan diambil datanya. Default pada halaman pertama (1)
     * @param int $count Jumlah data yang akan diambil. Default sebanyak 20 data
     * @param string $sorting_by Kolom untuk mengurutkan hasil. Default ke 'created_at'
     * @param string $sort Arah pengurutan, baik 'ASC' atau 'DESC'. Default ke 'DESC'
     * 
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    function getPaginateAll(int $page = 1, int $count = 10, string $sorting_by = 'created_at', $sort = 'DESC'): LengthAwarePaginator;

    /**
     * Mendapatkan daftar persyaratan berdasarkan id jenis perizinan
     * @param int $jenis_id
     * @return array
     */
    function getPersyaratan(int $jenis_id): array;

    /**
     * Mendapatkan daftar persyaratan berdasarkan id jenis perizinan dan id pemohon
     * @param int $jenis_id
     * @param int $pemohon_id
     * @return array
     */
    function getPersyaratanByJenisAndPemohon(int $jenis_id, int $pemohon_id): array;

    /**
     * Mengambil semua ID jenis perizinan yang memiliki template surat
     * 
     * @return array Semua ID
     */
    function getIdsWhereTemplateExists(): array;

    /**
     * Memeriksa apakah formulir entir data untuk jenis perizinan yang ditentukan ada.
     *
     * @param int|null $jenis_perizinan_id ID dari jenis perizinan.
     * @return bool True jika formulir data ada, false jika sebaliknya.
     */
    function hasEntriDataForm(?int $jenis_perizinan_id = null): bool;

}