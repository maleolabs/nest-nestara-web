<?php

namespace App\Services\DomainServices;

use App\Models\Izin;
use App\Services\DomainServices\Helpers\GetIzin;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Menyediakan antarmuka untuk mengelola perizinan dalam aplikasi.
 *
 * Antarmuka ini mendefinisikan metode-metode untuk menangani berbagai operasi terkait perizinan, seperti:
 * @method null|App\Models\Izin saveRegistration(int $jenis_izin_id, int $pemohon_id)
 * @method null|App\Models\Izin find(int $id)
 * @method Collection get(
 *        string $search = '',
 *       array $filter_status = [],
 *       array $filter_status_progress = [],
 *       array $filter_keterangan = [],
 *       string $filter_start_date = null,
 *       string $filter_end_date = null,
 *       string $sort = 'DESC',
 *       ?int $limit = null,
 *       string $order_by = 'created_at',
 *       bool $paginate = false,
 *       ?int $page = null)
 * @method array getAll(
 *      string $search = '', 
 *      array $filter_status = [], 
 *      array $filter_status_progress = [], 
 *      string $filter_start_date = null, 
 *      string $filter_end_date = null, 
 *      string $sort = 'DESC',
 *      ?int $limit = null,
 *      string $order_by = 'created_at')
 * @method array getAllBySprecificFilter(
 *      string $search = '',
 *      array $filter_status = [],
 *      array $filter_status_progress = [],
 *       array $filter_keterangan = [],
 *       string $filter_start_date = null,
 *       string $filter_end_date = null,
 *       string $sort = 'DESC',
 *       ?int $limit = null,
 *       string $order_by = 'created_at')
 * @method bool saveRegistrationAction(int $izin_id,
 *      string $lokasi_izin, 
 *      string $keterangan = null, 
 *      string $status_permohonan = 'permohonan-selesai', 
 *      int $pegawai_id = null)
 * @method int getCount(array $status, array $progres, array $keterangan)
 * @method void rollbackRegistration(int $izin_id, string $keterangan, int $pegawai_id)
 * @method void saveRegistrationRevision(int $izin_id, string $detail_keterangan = null)
 * @method array getAllStatusCancelable()
 * @method void cancelRegistration(int $zin_id, string $keterangan = null)
 * @method void delete(int $izin_id)
 * @method void updateIzinStatusThatHaveBeenReviewed()
 * @method void updateAllIzinStatusWhenEntriDataHasChanged()
 * @method bool hasDataEntri(int $izin_id)
 * @method void keepOldEntri(int $izin_id)
 * @method void updateStatusIfEntriDataAlrearyEntered(int $izin_id, ?int $pegawai_id = null)
 * @method void savePeninjauanLapangan(int $izin_id, int $penandatangan_id, ?string $nomor_surat = null)
 * @method void updateStatusAllIfTinjauanLapanganAlreadyEntered(int $pegawai_id)
 * @method void rekomendasiPermohonan(int $izin_id, int $pegawai_id, string $rekomendasi, string $jenis_kegiatan)
 * @method void penetapanIzin(int $izin_id, int $pegawai_id, string $tindakan)
 * @method void updateCetakIzinStatus(int $izin_id, int $pegawai_id)
 * @method void updateAllIzinStatusWhenAlreadyPrinted()
 * @method void penerbitanFinished(int $izin_id, int $pegawai_id = null)
 * @method void allDone(int $izin_id, int $pegawai_id = null)
 * @method array getIzinDetails(int $izin_id)
 */
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
     * Mengambil koleksi perizinan berdasarkan filter yang diberikan.
     *
     * @param string $search Kata kunci pencarian opsional. Jika tidak diberikan, semua perizinan akan dikembalikan.
     * @param array $filter_status Filter opsional berdasarkan status perizinan. Jika tidak diberikan, semua status akan dikembalikan.
     * @param array $filter_status_progress Filter opsional berdasarkan status kemajuan perizinan. Jika tidak diberikan, semua status kemajuan akan dikembalikan.
     * @param array $filter_keterangan Filter opsional berdasarkan deskripsi proses perizinan. Jika tidak diberikan, semua deskripsi akan dikembalikan.
     * @param string|null $filter_start_date Filter opsional berdasarkan tanggal mulai. Jika tidak diberikan, akan menggunakan satu bulan sebelum tanggal saat ini.
     * @param string|null $filter_end_date Filter opsional berdasarkan tanggal akhir. Jika tidak diberikan, akan menggunakan tanggal saat ini.
     * @param string $sort Urutan pengurutan opsional, bisa 'ASC' atau 'DESC'. Default adalah 'DESC'.
     * @param int|null $limit Batasan opsional pada jumlah hasil yang akan dikembalikan. Jika tidak diberikan, semua hasil akan dikembalikan.
     * @param string $order_by Kolom opsional untuk mengurutkan hasil. Default adalah 'created_at'.
     * @param bool $paginate Flag opsional untuk mengaktifkan paginasi. Default adalah false.
     * @param int|null $page Nomor halaman opsional untuk paginasi. Diperlukan jika $paginate bernilai true.
     * @return \Illuminate\Support\Collection|Illuminate\Pagination\LengthAwarePaginator|Illuminate\Pagination\CursorPaginator Koleksi model perizinan yang sesuai dengan filter yang diberikan.
     */
    function get(
        string $search = '',
        array $filter_status = [],
        array $filter_status_progress = [],
        array $filter_keterangan = [],
        string $filter_start_date = null,
        string $filter_end_date = null,
        string $sort = 'DESC',
        ?int $limit = null,
        string $order_by = 'created_at',
        bool $paginate = false,
        ?int $page = null
    ): Collection|LengthAwarePaginator|CursorPaginator;

    /**
     * Mengambil daftar perizinan dengan filter tertentu.
     *
     * @param string $search Kata kunci pencarian (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua perizinan.
     *
     * @param array $filter_status Filter berdasarkan status perizinan (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua status.
     *
     * @param array $filter_status_progress Filter berdasarkan status progres perizinan (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua status progres.
     *
     * @param string $filter_start_date Filter tanggal mulai perizinan (opsional, default: satu bulan sebelum hari ini). Jika tidak diberikan, akan menggunakan satu bulan sebelum hari ini.
     *
     * @param string $filter_end_date Filter tanggal akhir perizinan (opsional, default: hari ini). Jika tidak diberikan, akan menggunakan hari ini.
     *
     * @param string $sort Urutan pengurutan hasil (opsional, default: 'DESC').  Dapat berisi 'ASC' atau 'DESC'.
     * 
     * @param int|null $limit Jumlah data yang akan ditampilkan (opsional, default: semua).
     * 
     * @param string $order_by Kolom yang akan diurutkan (opsional, default: 'created_at').
     *
     * @return array Daftar perizinan yang sesuai dengan filter yang diberikan.
     */
    function getAll(
        string $search = '', 
        array $filter_status = [], 
        array $filter_status_progress = [], 
        string $filter_start_date = null, 
        string $filter_end_date = null, 
        string $sort = 'DESC',
        ?int $limit = null,
        string $order_by = 'created_at'
    ): array;

    /**
     * Mengambil daftar perizinan dengan filter tertentu.
     *
     * @param string $search Kata kunci pencarian (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua perizinan.
     *
     * @param array $filter_status Filter berdasarkan status perizinan (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua status.
     *
     * @param array $filter_status_progress Filter berdasarkan status progres perizinan (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua status progres.
     * 
     * @param array $filter_keterangan Filter berdasarkan keterangan proses perizinan (opsional, default: kosong). Jika tidak diberikan, akan mengembalikan semua keterangan.
     *
     * @param string $filter_start_date Filter tanggal mulai perizinan (opsional, default: satu bulan sebelum hari ini). Jika tidak diberikan, akan menggunakan satu bulan sebelum hari ini.
     *
     * @param string $filter_end_date Filter tanggal akhir perizinan (opsional, default: hari ini). Jika tidak diberikan, akan menggunakan hari ini.
     *
     * @param string $sort Urutan pengurutan hasil (opsional, default: 'DESC').  Dapat berisi 'ASC' atau 'DESC'.
     * 
     * @param int|null $limit Jumlah data yang akan ditampilkan (opsional, default: semua).
     * 
     * @param string $order_by Kolom yang digunakan untuk mengurutkan hasil (opsional, default: 'created_at').
     *
     * @return array Daftar perizinan yang sesuai dengan filter yang diberikan.
     */
    function getAllBySprecificFilter(
        string $search = '',
        array $filter_status = [],
        array $filter_status_progress = [],
        array $filter_keterangan = [],
        string $filter_start_date = null,
        string $filter_end_date = null,
        string $sort = 'DESC',
        ?int $limit = null,
        string $order_by = 'created_at'
    ): array;

    /**
     * Mendapatkan jumlah perizinan berdasarkan status, progres, dan keterangan.
     *
     * @param array $status Filter berdasarkan status perizinan.
     * @param array $progres Filter berdasarkan progres perizinan.
     * @param array $keterangan Filter berdasarkan keterangan perizinan.
     *
     * @return int Jumlah perizinan yang sesuai dengan filter yang diberikan.
     */
    function getCount(array $status, array $progres, array $keterangan): int;

    /**
     * Menyimpan data tindakan registrasi perizinan.
     *
     * @param int $izin_id ID perizinan yang terkait dengan tindakan registrasi.
     * @param string $lokasi_izin Lokasi perizinan yang ditinjau.
     * @param string|null $keterangan Keterangan tambahan terkait tindakan registrasi (opsional).
     * @param string $status_permohonan Menandakan status tindakan registrasi (permohonan-selesai atau revisi-permohonan).
     * @param int|null $pegawai_id ID pegawai yang melakukan tindakan registrasi (opsional).
     *
     * @return bool Mengembalikan nilai true jika penyimpanan data berhasil, false jika gagal.
     */
    function saveRegistrationAction(int $izin_id, string $lokasi_izin, string $keterangan = null, string $status_permohonan = 'permohonan-selesai', int $pegawai_id = null): bool;

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
     * @param string|null $detail_keterangan Detail keterangan atau alasan revisi perizinan (opsional).
     *
     * @return void
     */
    function saveRegistrationRevision(int $izin_id, string $detail_keterangan = null): void;

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

    /**
     * Memperbarui status perizinan jika data entri sudah dimasukkan.
     *
     * Fungsi ini memperbarui status perizinan berdasarkan apakah data entri
     * sudah dimasukkan atau belum. Jika data entri sudah dimasukkan, status
     * perizinan akan diperbarui.
     *
     * @param int $izin_id ID perizinan yang akan diperbarui statusnya.
     * @param ?int $pegawai_id ID pegawai yang terkait dengan perizinan (opsional).
     *
     * @return void Fungsi ini tidak mengembalikan nilai apa pun.
     */
    function updateStatusIfEntriDataAlrearyEntered(int $izin_id, ?int $pegawai_id = null): void;

    /**
     * Menyimpan hasil tinjuan lapangan untuk permohonan izin tertentu.
     *
     * @param int $izin_id ID permohonan izin yang akan disimpan hasil tinjauannya.
     * @param int $penandatangan_id ID penandatangan yang akan menandatangani surat.
     * @param string|null $nomor_surat Nomor surat yang akan disimpan (opsional).
     */
    function savePeninjauanLapangan(int $izin_id, int $penandatangan_id, ?string $nomor_surat = null): void;

    /**
     * Memperbarui status semua izin jika hasil tinjauan lapangan sudah dimasukkan.
     *
     * Fungsi ini memperbarui status perizinan berdasarkan apakah hasil tinjauan lapangan
     * sudah dimasukkan atau belum. Jika hasil tinjauan lapangan sudah dimasukkan, status
     * perizinan akan diperbarui.
     *
     * @param ?int $pegawai_id ID pegawai yang terkait dengan perizinan (opsional).
     *
     * @return void Fungsi ini tidak mengembalikan nilai apa pun.
     */
    function updateStatusAllIfTinjauanLapanganAlreadyEntered(int $pegawai_id): void;

    /**
     * Memverifikasi dan memberikan rekomendasi untuk permohonan izin.
     *
     * @param int $izin_id ID izin yang akan diverifikasi dan diberikan rekomendasi.
     * @param int $pegawai_id ID pegawai yang melakukan verifikasi dan memberikan rekomendasi.
     * @param string $rekomendasi Rekomendasi untuk permohonan izin.
     * @param string $jenis_kegiatan Jenis kegiatan yang terkait dengan permohonan izin.
     * 
     * @return void Fungsi ini tidak mengembalikan nilai apapun.
     */
    function rekomendasiPermohonan(int $izin_id, int $pegawai_id, string $rekomendasi, string $jenis_kegiatan): void;

    /**
     * Penetapan izin.
     *
     * Fungsi ini melakukan penetapan izin dengan ID tertentu, dilakukan oleh pegawai dengan ID tertentu, dan dengan tindakan tertentu.
     *
     * @param int $izin_id ID izin yang akan ditetapkan.
     * @param int $pegawai_id ID pegawai yang melakukan penetapan izin.
     * @param string $tindakan Tindakan yang dilakukan dalam penetapan izin.
     *
     * @return void Fungsi ini tidak mengembalikan nilai apa pun.
     */
    function penetapanIzin(int $izin_id, int $pegawai_id, string $tindakan): void;

    /**
     * Memperbarui status pencetakan izin untuk izin tertentu.
     *
     * @param int $izin_id ID izin yang akan diperbarui status pencetakannya.
     * @param int|null $pegawai_id ID pegawai yang melakukan pembaruan status pencetakan izin.
     */
    function updateCetakIzinStatus(int $izin_id, ?int $pegawai_id = null): void;

    /**
     * Memperbarui status semua izin jika surat izin sudah dicetak.
     *
     * @return void Fungsi ini tidak mengembalikan nilai apa pun.
     */
    function updateAllIzinStatusWhenAlreadyPrinted(): void;

    /**
     * Menandai izin yang diterbitkan sebagai selesai.
     *
     * @param int $izin_id ID dari izin yang akan ditandai sebagai selesai.
     * 
     * @param int|null $pegawai_id ID pegawai yang melakukan penandatanganan izin (Optional).
     *
     * @return void Fungsi ini tidak mengembalikan apa-apa.
     */
    function penerbitanFinished(int $izin_id, int $pegawai_id = null): void;

    /**
     * Menandai izin yang diterbitkan sebagai selesai.
     *
     * @param int $izin_id ID dari izin yang akan ditandai sebagai selesai.
     * @param int|null $pegawai_id ID pegawai yang melakukan penandatanganan izin (Optional).
     *
     * @return void Fungsi ini tidak mengembalikan apa-apa.
     */
    function allDone(int $izin_id, int $pegawai_id = null): void;

    /**
     * Mengambil detail dari permohonan izin.
     *
     * @param int $izin_id ID dari permohonan izin yang akan diambil detailnya.
     * @return array Detail dari permohonan izin.
     */
    function getIzinDetails(int $izin_id): array;

}