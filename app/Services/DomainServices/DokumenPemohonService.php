<?php

namespace App\Services\DomainServices;

use App\Models\DokumenPemohon;

interface DokumenPemohonService {

	/**
	* Menyimpan data dokumen pemohon
	* @param array $data_dokumenpemohon Data dokumen pemohon
	* @return DokumenPemohon|null Objek DokumenPemohon yang disimpan atau null jika gagal
	*/
	function save(array $data_dokumenpemohon): ?DokumenPemohon;

	/**
	* Menyimpan semua dokumen persyaratan untuk pemohon dan izin tertentu
	* @param int $pemohon_id ID pemohon
	* @param int $izin_id ID izin
	* @param array $data_persyaratan Data persyaratan
	* @return void
	*/
	function saveAll(int $pemohon_id, int $izin_id, array $data_persyaratan): void;

	/**
	* Menyimpan file alternatif untuk dokumen persyaratan
	* @param int $pemohon_id ID pemohon
	* @param int $syarat_izin_id ID syarat izin
	* @param int $izin_id ID izin
	* @param string $nama_file_alternatif Nama file alternatif
	* @return DokumenPemohon|null Objek DokumenPemohon yang disimpan atau null jika gagal
	*/
	function saveFileAlternates(int $pemohon_id, int $syarat_izin_id, int $izin_id, string $nama_file_alternatif): ?DokumenPemohon;

	/**
	* Menyimpan semua file alternatif untuk dokumen pemohon
	* @param int $pemohon_id ID pemohon
	* @param int $izin_id ID izin
	* @param array $alt_files Array dari file alternatif yang akan disimpan dengan array key adalah ID syarat izin
	* @return void
	*/
	function saveAllFileAlternates(int $pemohon_id, int $izin_id, array $alt_files): void;

	/**
	* Mencari dokumen pemohon berdasarkan ID
	* @param int $id ID dokumen pemohon
	* @return DokumenPemohon|null Objek DokumenPemohon yang ditemukan atau null jika tidak ditemukan
	*/
	function find(int $id): ?DokumenPemohon;

	/**
	* Mencari dokumen pemohon berdasarkan pemohon, syarat izin, dan izin
	* @param int $pemohon_id ID pemohon
	* @param int $syarat_izin_id ID syarat izin
	* @param int $izin_id ID izin
	* @return DokumenPemohon|null Objek DokumenPemohon jika ditemukan, null jika tidak ditemukan
	*/
	function findByPemohonAndSyaratAndIzin(int $pemohon_id, int $syarat_izin_id, int $izin_id): ?DokumenPemohon;

	/**
	* Mendapatkan semua dokumen pemohon berdasarkan pemohon, jenis perizinan, dan izin
	* @param int $pemohon_id ID pemohon
	* @param int $jenis_perizinan_id ID jenis izin
	* @param int $izin_id ID izin
	* @return array Array dari objek DokumenPemohon
	*/
	function getAllByPemohonAndSyaratAndIzin(int $pemohon_id, int $jenis_perizinan_id, int $izin_id): array;

	/**
	* Menghapus dokumen pemohon berdasarkan pemohon dan syarat izin
	* @param int $pemohon_id ID pemohon
	* @param int $syarat_izin_id ID syarat izin
	* @return bool True jika berhasil dihapus, false jika gagal
	*/
	function deleteByPemohonAndSyaratIzin(int $pemohon_id, int $syarat_izin_id): bool;	

}