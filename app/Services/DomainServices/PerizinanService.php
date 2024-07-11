<?php

namespace App\Services\DomainServices;

use App\Models\Izin;

interface PerizinanService
{
 
    /**
     * Menyimpan data registrasi perizinan baru.
     *
     * @param int $jenis_izin_id ID jenis perizinan yang akan didaftarkan.
     * @param int $pemohon_id ID pemohon yang mengajukan perizinan.
     *
     * @return \App\Models\Izin|null Objek perizinan yang baru didaftarkan, atau null jika gagal.
     */
    function saveRegistration(int $jenis_izin_id, int $pemohon_id): ?Izin;

    /**
     * Mencari perizinan berdasarkan ID.
     *
     * @param int $id ID perizinan yang ingin dicari.
     *
     * @return \App\Models\Izin|null Objek perizinan jika ditemukan, atau null jika tidak ditemukan.
     */
    function find(int $id): ?Izin;

    /**
     * Mengambil daftar perizinan dengan filter tertentu.
     *
     * @param string $search Kata kunci pencarian (opsional, default: kosong).
     *                       Jika tidak diberikan, akan mengembalikan semua perizinan.
     *
     * @param array $filter_status Filter berdasarkan status perizinan (opsional, default: kosong).
     *                             Jika tidak diberikan, akan mengembalikan semua status.
     *
     * @param array $filter_status_progress Filter berdasarkan status progres perizinan (opsional, default: kosong).
     *                                      Jika tidak diberikan, akan mengembalikan semua status progres.
     *
     * @param string $filter_start_date Filter tanggal mulai perizinan (opsional, default: satu bulan sebelum hari ini).
     *                                  Jika tidak diberikan, akan menggunakan satu bulan sebelum hari ini.
     *
     * @param string $filter_end_date Filter tanggal akhir perizinan (opsional, default: hari ini).
     *                                Jika tidak diberikan, akan menggunakan hari ini.
     *
     * @param string $sort Urutan pengurutan hasil (opsional, default: 'DESC').
     *                     Dapat berisi 'ASC' atau 'DESC'.
     *
     * @return array Daftar perizinan yang sesuai dengan filter yang diberikan.
     */
    function getAll(
        string $search = '', 
        array $filter_status = [], 
        array $filter_status_progress = [], 
        string $filter_start_date = null, 
        string $filter_end_date = null, 
        string $sort = 'DESC'
    ): array;

    /**
     * Menyimpan data tindakan registrasi perizinan.
     *
     * @param int $izin_id ID perizinan yang terkait dengan tindakan registrasi.
     * @param string $tanggal_peninjauan Tanggal peninjauan lokasi perizinan.
     * @param string $lokasi_izin Lokasi perizinan yang ditinjau.
     * @param string|null $keterangan Keterangan tambahan terkait tindakan registrasi (opsional).
     * @param string $status_permohonan Menandakan status tindakan registrasi (permohonan-selesai atau revisi-permohonan).
     * @param int|null $pegawai_id ID pegawai yang melakukan tindakan registrasi (opsional).
     *
     * @return bool Mengembalikan nilai true jika penyimpanan data berhasil, false jika gagal.
     */
    function saveRegistrationAction(int $izin_id, string $tanggal_peninjauan, string $lokasi_izin, string $keterangan = null, string $status_permohonan = 'permohonan-selesai', int $pegawai_id = null): bool;

    /**
     * Melakukan pembatalan atau revisi terhadap perizinan yang telah didaftarkan.
     *
     * @param int $izin_id ID perizinan yang akan dibatalkan atau direvisi.
     * @param string $keterangan Keterangan atau alasan pembatalan atau revisi perizinan.
     * @param int $pegawai_id ID pegawai yang melakukan pembatalan atau revisi perizinan.
     *
     * @return void
     */
    function rollbackRegistration(int $izin_id, string $keterangan, int $pegawai_id): void;

    /**
     * Menyimpan revisi atau perubahan pada data registrasi perizinan.
     *
     * @param int $izin_id ID perizinan yang akan direvisi.
     * @param string|null $keterangan Keterangan atau alasan revisi perizinan (opsional).
     *
     * @return void
     */
    function saveRegistrationRevision(int $izin_id, string $keterangan = null): void;

    /**
     * Mendapatkan semua status perizinan yang dapat dibatalkan.
     *
     * @return array
     */
    function getAllStatusCancelable(): array;

    /**
     * Membatalkan registrasi perizinan yang telah didaftarkan.
     *
     * @param int $izin_id ID perizinan yang akan dibatalkan.
     * @param string|null $keterangan Keterangan atau alasan pembatalan registrasi perizinan (opsional).
     *
     * @return void
     */
    function cancelRegistration(int $zin_id, string $keterangan = null): void;

    /**
     * Menghapus data perizinan berdasarkan ID.
     *
     * @param int $izin_id ID perizinan yang akan dihapus.
     *
     * @return void
     */
    function delete(int $izin_id): void;

    /**
     * Memperbarui status perizinan yang telah ditinjau atau direvisi.
     *
     * @return void
     */
    function updateIzinStatusThatHaveBeenReviewed(): void;

    /**
     * Memperbarui status semua perizinan ketika terjadi perubahan pada data entri.
     *
     * @return void
     */
    function updateAllIzinStatusWhenEntriDataHasChanged(): void;

    /**
     * Memperbarui status perizinan tertentu ketika terjadi perubahan pada data entri.
     *
     * @param int $izin_id ID perizinan yang akan diperbarui statusnya.
     *
     * @return void
     */
    function updateIzinStatusWhenEntriDataHasChanged(int $izin_id): void;

    /**
     * Memeriksa apakah sudah ada entri data untuk ID izin tertentu.
     *
     * Fungsi ini memverifikasi apakah sudah ada entri data yang terkait
     * dengan ID izin yang diberikan.
     *
     * @param int $izin_id ID izin yang akan diperiksa entri datanya.
     * 
     * @return bool Mengembalikan true jika sudah ada entri data untuk ID izin yang diberikan, false jika tidak ada.
     */
    function hasDataEntri(int $izin_id): bool;

    /**
     * Mengatur agar izin mengambil data entri lama untuk ID izin tertentu.
     *
     * @param int $izin_id ID izin yang akan diatur untuk mengambil data entri lama.
     * 
     * @return void Fungsi ini tidak mengembalikan nilai apa pun.
     */
    function keepOldEntri(int $izin_id): void;

    function updateStatusIfEntriDataAlrearyEntered(int $izin_id, ?int $pegawai_id = null): void;
    
}