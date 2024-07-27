<?php

namespace App\Services\DomainServices;

use App\Models\SyaratIzin;

interface SyaratIzinService 
{

	/**
	 * Menyimpan field syarat tertentu
	 * 
	 * @param int $syarat_id ID dari syarat izin yang akan disimpan
	 * @param string $field Nama field dari syarat izin yang akan disimpan
	 * @param mixed $value Value dari field syarat izin yang akan disimpan
	 * 
	 * @return void Tidak mengembalikan apa-apa
	 */
	function saveField(int $syarat_id, string $field, $value): void;

	/**
	* Mencari data syarat izin berdasarkan ID
	* @param int $id ID syarat izin yang dicari
	* @return SyaratIzin|null Objek SyaratIzin yang ditemukan atau null jika tidak ditemukan
	*/
	function find(int $id): ?SyaratIzin;

	/**
	* Menghapus data syarat izin
	* @param int $id ID syarat izin yang akan dihapus
	* @return bool True jika berhasil dihapus, false jika gagal
	*/
	function delete(int $id): bool;

	/**
	* Menghubungkan syarat izin dengan jenis izin berdasarkan nama
	* @param string $nama_syarat Nama syarat izin
	* @param string $nama_jenis Nama jenis izin
	* @return void
	*/
	function assignSyaratToJenisByName(string $nama_syarat, string $nama_jenis): void;

	/**
	* Menghubungkan beberapa syarat izin dengan jenis izin berdasarkan nama
	* @param array $syarat Semua syarat dengan format array 'id' => 'value'
	* @param int $id_jenis ID jenis izin
	* @return void
	*/
	function assignAllSyaratToJenisByName(array $syarat, int $id_jenis): void;

	/**
	 * Menghapus relasi syarat izin dan perizinan berdasarkan ID syarat dan ID jenis perizinan tertentu
	 * 
	 * @param int $syarat_id ID dari syarat yang akan di hapus relasinya
	 * @param int $jenis_izin_id ID dari jenis izin yang akan di hapus relasinya
	 * @return void Tidak mengembalikan nilai apa-apa
	 */
	function detachSyarat(int $syarat_id, int $jenis_izin_id): void;

	/**
	* Mendapatkan daftar syarat izin berdasarkan jenis izin dan pemohon
	* @param int $jenis_izin_id ID jenis izin
	* @param int $pemohon_id ID pemohon
	* @return array Daftar syarat izin
	*/
	function getByJenisAndPemohon(int $jenis_izin_id, int $pemohon_id): array;

	/**
	* Mendapatkan daftar tipe PDF syarat izin berdasarkan jenis izin dan pemohon
	* @param int $jenis_izin_id ID jenis izin
	* @param int $pemohon_id ID pemohon
	* @return array Daftar tipe PDF syarat izin
	*/
	function getIdsTypePdfByJenisAndPemohon(int $jenis_izin_id, int $pemohon_id): array;
	
	/**
	 * Mengambil semua persyaratan izin yang tidak terkait dengan jenis perizinan tertentu
	 * 
	 * @param int $jenis_izin_id ID dari jenis izin yang akan dikecualikan untuk pengambilan data persyartan
	* @param string $search Nama syarat yang ingin dicari. Default ''
	 * @param int $limit Batas jumlah data yang akan dimuat. Default 10
	 * @return array Semua persyaratan izin kecuali yang tidak terkait dengan jenis perizinan tertentu 
	 */
	function getAllWhereNotInJenisPerizinan(int $jenis_izin_id, string $search = '', int $limit = 10): array;

}