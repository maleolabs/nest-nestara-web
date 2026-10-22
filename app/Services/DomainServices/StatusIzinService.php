<?php

namespace App\Services\DomainServices;

use App\Models\StatusIzin;

interface StatusIzinService {

	/**
	 * Menyimpan data status izin baru
	 * @param int $izin_id ID izin yang terkait
	 * @param string $nama_status Nama status izin
	 * @return StatusIzin|null Objek StatusIzin yang disimpan, atau null jika gagal
	 */
	function save(int $izin_id, string $nama_status): ?StatusIzin;

	/**
	 * Mencari data status izin berdasarkan nama dan ID izin
	 * @param string $nama Nama status izin
	 * @param int $izin_id ID izin yang terkait
	 * @return StatusIzin|null Objek StatusIzin yang ditemukan, atau null jika tidak ada
	 */
	function findByNamaAndIzin(string $nama, int $izin_id): ?StatusIzin;

	/**
	 * Mencari status izin terbaru berdasarkan ID izin
	 * @param int $izin_id ID izin yang terkait
	 * @return StatusIzin|null Status izin terbaru, atau null jika tidak ditemukan
	 */
	function findLastStatusByIzin(int $izin_id): ?StatusIzin;

	/**
	 * Mengambil riwayat proses untuk ID status tertentu.
	 *
	 * @param int $status_id ID status untuk mengambil riwayat proses.
	 * @return array Array yang berisi riwayat proses untuk ID status yang diberikan.
	 */
	function getRiwayatProses(int $status_id): array;

	/**
	 * Mengambil riwayat proses untuk ID izin tertentu.
	 *
	 * @param int $izin_id ID izin untuk mengambil riwayat proses.
	 * @return array Array yang berisi riwayat proses untuk ID izin yang diberikan.
	 */
	function getRiwayatProsesByIzin(int $izin_id): array;

	/**
		* Menyimpan banyak data status izin sekaligus
		* @param array $status_izin_data Array data status izin
		* @return void
		*/
	function saveAllStatusIzin(array $status_izin_data): void;

	/**
	 * Memperbarui data status izin
	 * @param int $izin_id ID izin yang terkait
	 * @param string $status Status izin terbaru
	 * @param string $progres Progres izin terbaru
	 * @param string|null $ket Keterangan tambahan (opsional)
	 * @param int|null $pegawai_id ID pegawai yang memperbarui (opsional)
	 * @param mixed $updated_at Waktu pembaruan (opsional). Jika null, akan mengambil waktu sekarang
	 * @param bool $new Apakah data baru atau pembaruan
	 * @param bool $keep_pegawai Apakah pegawai harus tetap digunakan (opsional)
	 * @return void
	 */
	function updateStatusIzin(int $izin_id, string $status, string $progres, ?string $ket = null, ?int $pegawai_id = null, $updated_at = null, bool $new = false, bool $keep_pegawai = false): void;

	/**
	 * Memperbarui status izin dengan detail keterangan tambahan.
	 * @param int $izin_id ID izin yang terkait
	 * @param string $status Status izin terbaru
	 * @param string $progres Progres izin terbaru
	 * @param string|null $keterangan Keterangan tambahan (opsional)
	 * @param string $detail_keterangan Detail keterangan tambahan
	 * @return void
	 */
	function updateStatusIzinWithDetailKeterangan(int $izin_id, string $status, string $progres, ?string $keterangan = null, ?string $detail_keterangan = null): void;
	function appendStatusIzinWithDetail(int $izin_id, string $status, string $progres, string $keterangan, string $detail_keterangan, ?int $pegawai_id = null): void;

	/**
	 * Menghapus semua data status izin berdasarkan ID izin
	 * @param int $izin_id ID izin yang terkait
	 * @return void
	 */
	function deleteAllByIzinId(int $izin_id): void;

}
