<?php

namespace App\Services\DomainServices;

interface SuratService {

    /**
     * Membuat nomor surat baru untuk jenis_perizinan_id yang ditentukan.
     *
     * @param int $jenis_perizinan_id ID jenis perizinan (tipe izin) untuk membuat nomor surat.
     * @return string Nomor surat yang dibuat.
     */
    function generateNomorSurat(int $jenis_perizinan_id): string;

    /**
     * Mendapatkan format untuk surat berdasarkan jenis perizinan.
     *
     * @param int $jenis_perizinan_id ID jenis perizinan untuk mendapatkan format surat.
     * @param bool $clear_unreplace_format Menentukan apakah format yang tidak tergantikan harus dihapus atau tidak.
     * @return string Format untuk surat yang sesuai dengan jenis perizinan.
     */
    function getFormat(int $jenis_perizinan_id, bool $clear_unreplace_format = true): string;

    /**
     * Mendapatkan format surat yang sebenarnya.
     *
     * @return string Format surat yang sesuai dengan jenis perizinan.
     */
    function getActualFormat(): string;

    /**
     * Mendapatkan nomor surat terakhir.
     * 
     * @return int Nomor surat terakhir.
     */
    function getLastNomor(): int;

    /**
     * Mendapatkan nomor surat saat ini.
     *
     * @return int Nomor surat saat ini.
     */
    function getNomor(): int;

    /**
     * Menyimpan nomor surat
     * 
     * @param int $nomor_terakhir Nomor surat terakhir
     */
    function saveNomor(int $nomor_terakhir): void;

    /**
     * Memperbarui nomor surat untuk jenis perizinan yang ditentukan.
     *
     * @param int|null $nomor_terakhir Nomor surat terakhir yang digunakan. Jika null, nomor surat akan dimulai dari 1.
     * @param int|null $jenis_perizinan_id ID jenis perizinan untuk memperbarui nomor surat. Jika null, format default akan digunakan.
     * @param int|null $nomor_terakhir_jenis_perizinan Nomor surat terakhir untuk jenis perizinan yang ditentukan. Jika null, nomor surat akan dimulai dari 1.
     */
    function updateNomorSurat(?int $nomor_terakhir = null, ?int $jenis_perizinan_id = null, ?int $nomor_terakhir_jenis_perizinan = null): void;

    /**
     * Mendapatkan format header untuk surat.
     *
     * @return array Format header untuk surat.
     */
    function getKopSurat(): array;

    /**
     * Menyimpan kop surat
     * 
     * @param array $kop_data Data dari kop surat yang akan disimpan
     */
    function saveKopSurat(array $kop_data): void;

    /**
     * Menyimpan tempat penetapan surat
     * 
     * @param string $tempat_penetapan Nama tempat penetapan surat
     */
    function saveTempatPenetapan(string $tempat_penetapan): void;

    /**
     * Mendapatkan konten surat berdasarkan Jenis Perizinan tertentu.
     * 
     * @param int $jenis_perizinan_id ID dari jenis perizinan.
     * 
     * @return array Kontent surat dari Jenis Perizinan tertentu.
     */
    function getSuratContentByJenis(int $jenis_perizinan_id): array;

    /**
     * Membuat format default untuk nomor surat.
     */
    function generateDefaultFormatSurat(): void;

    /**
     * Menghasilkan format header default untuk surat.
     */
    function generateDefaultKopSurat(): void;

    /**
     * Membuat format default untuk jenis perizinan.
     */
    function generateDefaultJenisPerizinanFormat(): void;

    /**
     * Menetapkan template default untuk jenis surat.
     */
    function setDefaultJenisSuratTemplate(): void;

}