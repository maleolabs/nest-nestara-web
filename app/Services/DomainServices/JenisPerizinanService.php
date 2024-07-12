<?php

namespace App\Services\DomainServices;

use App\Models\JenisPerizinan;

interface JenisPerizinanService {

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
     * Mengambil semua catatan jenis perizinan, secara opsional diurutkan berdasarkan kolom dan arah tertentu.
     *
     * @param string $sorting_by Kolom untuk mengurutkan hasil. Default ke 'created_at'.
     * @param string $sort Arah pengurutan, baik 'ASC' atau 'DESC'. Default ke 'DESC'.
     * @return array Sebuah array dari model JenisPerizinan.
     */
    function getAll(string $sorting_by = 'created_at', string $sort = 'DESC'): array;

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

}