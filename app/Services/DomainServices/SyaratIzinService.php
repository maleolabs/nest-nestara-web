<?php

namespace App\Services\DomainServices;

use App\Models\SyaratIzin;

interface SyaratIzinService 
{

	/**
	* Menyimpan data syarat izin baru
	* @param array $data_syaratizin Data syarat izin yang akan disimpan
	* @return SyaratIzin|null Objek SyaratIzin yang baru disimpan atau null jika gagal
	*/
	function save(array $data_syaratizin): ?SyaratIzin;

	/**
	* Mencari data syarat izin berdasarkan ID
	* @param int $id ID syarat izin yang dicari
	* @return SyaratIzin|null Objek SyaratIzin yang ditemukan atau null jika tidak ditemukan
	*/
	function find(int $id): ?SyaratIzin;

	/**
	* Memperbarui data syarat izin
	* @param int $id ID syarat izin yang akan diperbarui
	* @param array $data_syaratizin Data syarat izin yang baru
	* @return SyaratIzin|null Objek SyaratIzin yang baru diperbarui atau null jika gagal
	*/
	function update(int $id, array $data_syaratizin): ?SyaratIzin;

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

}