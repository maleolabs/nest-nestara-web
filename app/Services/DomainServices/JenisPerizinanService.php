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
     * Menyimpan data perizinan untuk field tertentu
     * 
     * @param int $id ID dari jenis perizinan yang akan disimpan
     * @param string $field Nama field untuk disimpan
     * @param string $value Value dari field untuk disimpan
     * @return App\Models\JenisPerizinan Model yang sudah diupdate
     */
    function saveField(int $id, string $field, string $value): ?JenisPerizinan;

    /**
     * Menyimpan pengaturan surat berdasarkan ID dari jenis perizinan tertentu
     * 
     * @param string $pengaturan Nama pengaturan yang akan disimpan
     * @param string $value Isi dari pengaturan yang akan disimpan
     * @param int $jenid_id ID dari jenis perizinan
     * 
     * @return void Tidak mengembalikan apa-apa
     */
    function savePengaturanSurat(string $pengaturan, string $value, int $jenis_id): void;

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
     * Mencari jenis perizinan berdasarkan nama
     * 
     * @param string $nama Nama dari jenis perizinan yang akan dicari
     * @param int|null $limit Batas jumlah data yang akan diambil
     * @return array Semua nama yang mirip dari nama jenis perizinan yang dicari
     */
    function searchByName(string $nama, ?int $limit = null): array;

    /**
     * Mendapatkan daftar persyaratan berdasarkan id jenis perizinan
     * @param int $jenis_id
     * @return array
     */
    function getPersyaratan(int $jenis_id): array;

    /**
     * Mendapatkan daftar data persyaratan berdasarkan id jenis perizinan
     * @param int $jenis_id
     * @return array
     */
    function getDataPersyaratan(int $jenis_id): array;

    /**
     * Mendapatkan daftar persyaratan berdasarkan id jenis perizinan dan id pemohon
     * @param int $jenis_id
     * @param int $pemohon_id
     * @return array
     */
    function getPersyaratanByJenisAndPemohon(int $jenis_id, int $pemohon_id): array;

    /**
     * Mendapatkan semua file template pdf berdasarkan ID dari jenis perizinan tertentu
     * 
     * @param int $jenis_id ID dari jenis perizinan yang akan diambil semua file template pdfnya
     * @return array Semua file pdf
     */
    function getAllTemplateById(int $jenis_id): array;

    /**
     * Mengecek apakah template pdf bisa digunakan atau tidak berdasarkan ID dari jenis perizinan tertentu
     * 
     * @param int $jenis_id ID dari jenis perizinan yang akan dicek
     * @return bool True jika template bisa digunakan, dan False jika template tidak bisa digunakan atau kosong.
     */
    function canUseTemplateById(int $jenis_id): bool;

    /**
     * Mengambil semua ID jenis perizinan yang memiliki template surat
     * 
     * @return array Semua ID
     */
    function getIdsWhereTemplateExists(): array;

    /**
     * Mengambil format surat untuk jenis perizinan dengan ID tertentu
     * 
     * @param int $jenis_id ID dari jenis perizinan
     * @return string|null Format surat atau null
     */
    function getJenisFormatSuratById(int $jenis_id): ?string;

    /**
     * Mengambil nomor surat untuk jenis perizinan dengan ID tertentu
     * 
     * @param int $jenis_id ID dari jenis perizinan
     * @return int|null Nomor surat atau null
     */
    function getNomorSuratById(int $jenis_id): ?int;

    /**
     * Mengambil template surat untuk jenis perizinan dengan ID tertentu
     * 
     * @param int $jenis_id ID dari jenis perizinan
     * @return string|null Template surat atau null
     */
    function getCurrentTemplateById(int $jenis_id): ?string;

    /**
     * Memeriksa apakah formulir entir data untuk jenis perizinan yang ditentukan ada.
     *
     * @param int|null $jenis_perizinan_id ID dari jenis perizinan.
     * @return bool True jika formulir data ada, false jika sebaliknya.
     */
    function hasEntriDataForm(?int $jenis_perizinan_id = null): bool;

    /**
     * Menghapus jenis perizinan berdasarkan ID tertentu
     * 
     * @param int $jenis_id ID dari jenis perizinan yang akan dihapus
     * @return void
     */
    function delete(int $jenis_id): void;

}