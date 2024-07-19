<?php

namespace App\Services\DomainServices;

use App\Models\EntriData;
use App\Models\EntriDataIzin;

interface EntriDataService {

    /**
	 * Menyimpan data entri.
	 *
	 * Fungsi ini menyimpan data entri yang diberikan dalam bentuk array.
	 *
	 * @param array $data_entridata Data entri yang akan disimpan.
	 * 
	 * @return EntriData|null Mengembalikan objek EntriData jika penyimpanan berhasil, atau null jika gagal.
	 */
	function save(array $data_entridata): ?EntriData;

	/**
	 * Menemukan data entri berdasarkan ID.
	 *
	 * Fungsi ini mencari dan mengembalikan data entri yang sesuai dengan ID yang diberikan.
	 *
	 * @param int $id ID data entri yang akan dicari.
	 * 
	 * @return EntriData|null Mengembalikan objek EntriData jika ditemukan, atau null jika tidak ditemukan.
	 */
	function find(int $id): ?EntriData;

	/**
	 * Menemukan entri data izin berdasarkan entri data id
	 * Jika entri data telah dihapus, fungsi ini akan mencoba
	 * mengambil entri data yang telah dihapus berdasarkan nilai ambil_entri_lama
	 * pada model Izin.
	 * 
	 * @param int $data_id ID entri data yang akan dicari
	 * 
	 * @param int $izin_id ID izin yang akan dicari
	 * 
	 * @return EntriDataIzin|null
	 */
	function findFirstEntriIzinByDataId(int $data_id, int $izin_id): ?EntriDataIzin;

	/**
	 * Memperbarui data entri berdasarkan ID.
	 *
	 * Fungsi ini memperbarui data entri yang sesuai dengan ID dan data yang diberikan.
	 *
	 * @param int $id ID data entri yang akan diperbarui.
	 * @param array $data_entridata Data entri yang akan diperbarui.
	 * 
	 * @return EntriData|null Mengembalikan objek EntriData yang diperbarui jika berhasil, atau null jika gagal.
	 */
	function update(int $id, array $data_entridata): ?EntriData;

	/**
	 * Menghapus data entri berdasarkan ID.
	 *
	 * Fungsi ini menghapus data entri yang sesuai dengan ID yang diberikan.
	 *
	 * @param int $id ID data entri yang akan dihapus.
	 * 
	 * @return bool Mengembalikan true jika penghapusan berhasil, atau false jika gagal.
	 */
	function delete(int $id): bool;

	/**
	 * Mengambil data entri berdasarkan ID jenis perizinan.
	 *
	 * Fungsi ini mencari dan mengembalikan data entri yang sesuai dengan ID jenis perizinan yang diberikan.
	 *
	 * @param int $jenis_perizinan_id ID jenis perizinan yang akan dicari data entrinya.
	 * 
	 * @return array Mengembalikan array data entri yang sesuai dengan ID jenis perizinan.
	 */
	function getByJenisPerizinanId(int $jenis_perizinan_id): array;

	/**
	 * Mengambil data entri berdasarkan ID jenis perizinan dan ID izin yang menggunakan data lama.
	 *
	 * Fungsi ini mencari dan mengembalikan data entri yang sesuai dengan ID jenis perizinan dan ID izin yang menggunakan data lama.
	 *
	 * @param int $jenis_perizinan_id ID jenis perizinan yang akan dicari data entrinya.
	 * @param int $izin_id ID izin yang akan menggunakan data entri lama.
	 * 
	 * @return array Mengembalikan array data entri yang sesuai dengan ID jenis perizinan dan ID izin.
	 */
	function getByJenisPerizinanIdWithOldData(int $jenis_perizinan_id, int $izin_id): array;

	/**
	 * Mengambil data izin yang dikelompokkan berdasarkan nama.
	 *
	 * Fungsi ini mencari dan mengembalikan data izin yang dikelompokkan berdasarkan nama.
	 *
	 * @param int $izin_id ID izin yang akan diambil data izinnya.
	 * @return array Mengembalikan array data izin yang dikelompokkan berdasarkan nama.
	 */
	function getDataIzinGroupByNama(int $izin_id): array;

	/**
	 * Menyimpan satu atau beberapa data entri izin.
	 *
	 * Fungsi ini menyimpan satu atau beberapa data entri izin yang diberikan.
	 *
	 * @param mixed $entri_data_izin Data entri izin yang akan disimpan.
	 * @param int $izin_id ID izin yang terkait dengan data entri.
	 * @param string|null $value Nilai opsional yang akan disimpan bersama data entri.
	 * 
	 * @return array|null Mengembalikan array data entri izin yang disimpan jika berhasil, atau null jika gagal.
	 */
	function saveEntriDataIzin($entri_data_izin, int $izin_id, string $value = null): ?array;

	/**
	 * Memeriksa apakah data entri izin sudah ada berdasarkan ID izin dan ID jenis perizinan.
	 *
	 * Fungsi ini memverifikasi apakah data entri izin sudah ada dengan ID izin dan ID jenis perizinan yang diberikan.
	 *
	 * @param int $izin_id ID izin yang akan diperiksa.
	 * @param int $jenis_perizinan_id ID jenis perizinan yang akan diperiksa.
	 * 
	 * @return bool Mengembalikan true jika data entri izin sudah ada, atau false jika belum ada.
	 */
	function isEntriDataIzinExistsByIzinAndJenis(int $izin_id, int $jenis_perizinan_id): bool;

	/**
	 * Mengambil data lama yang telah dihapus secara lunak (soft deleted).
	 *
	 * Fungsi ini mencari dan mengembalikan data entri lama yang telah dihapus secara lunak (soft deleted) berdasarkan objek Izin yang diberikan.
	 *
	 * @param \App\Models\Izin $izin Objek Izin yang terkait dengan data entri yang akan diambil.
	 * 
	 * @return array Mengembalikan array data entri lama yang telah dihapus secara lunak.
	 */
	function getOldEntriDataByIzin(\App\Models\Izin $izin): array;

	/**
	 * Memeriksa apakah ada data entri izin yang dihapus secara lunak (soft deleted).
	 *
	 * Fungsi ini memverifikasi apakah ada data entri izin yang telah dihapus secara lunak (soft deleted) berdasarkan ID izin yang diberikan.
	 *
	 * @param int $izin_id ID izin yang akan diperiksa.
	 * 
	 * @return bool Mengembalikan true jika ada data entri izin yang dihapus secara lunak, atau false jika tidak ada.
	 */
	function hasTrashedEntriDataIzin(int $izin_id): bool;

	/**
	 * Mengambil tanggal penghapusan berdasarkan ID jenis perizinan.
	 *
	 * Fungsi ini mencari dan mengembalikan tanggal penghapusan data entri yang sesuai dengan ID jenis perizinan yang diberikan.
	 *
	 * @param int $jenis_perizinan_id ID jenis perizinan yang akan dicari tanggal penghapusannya.
	 * 
	 * @return string|null Mengembalikan string tanggal penghapusan jika ditemukan, atau null jika tidak ditemukan.
	 */
	function getDeletedAtByJenis(int $jenis_perizinan_id): ?string;
	/**
	 * Menghapus data entri lama berdasarkan ID jenis perizinan.
	 *
	 * Fungsi ini menghapus data entri lama yang sesuai dengan ID jenis perizinan yang diberikan.
	 *
	 * @param int $jenis_perizinan_id ID jenis perizinan yang akan dihapus data entri lamanya.
	 * 
	 * @return void Fungsi ini tidak mengembalikan nilai apa pun.
	 */
	function deleteOldEntriData(int $jenis_perizinan_id): void;

}