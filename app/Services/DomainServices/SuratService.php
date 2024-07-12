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
     * Mendapatkan nomor surat saat ini berdasarkan jenis perizinan.
     *
     * @return int Nomor surat saat ini untuk jenis perizinan yang ditentukan.
     */
    function getNomor(): int;

    /**
     * Memperbarui nomor surat untuk jenis perizinan yang ditentukan.
     *
     * @param int|null $nomor_terakhir Nomor surat terakhir yang digunakan. Jika null, nomor surat akan dimulai dari 1.
     * @param int|null $jenis_perizinan_id ID jenis perizinan untuk memperbarui nomor surat. Jika null, format default akan digunakan.
     * @param int|null $nomor_terakhir_jenis_perizinan Nomor surat terakhir untuk jenis perizinan yang ditentukan. Jika null, nomor surat akan dimulai dari 1.
     */
    function updateNomorSurat(?int $nomor_terakhir = null, ?int $jenis_perizinan_id = null, ?int $nomor_terakhir_jenis_perizinan = null): void;

    /**
     * Membuat format default untuk nomor surat.
     */
    function generateDefaultFormatSurat(): void;

    /**
     * Membuat format default untuk jenis perizinan.
     */
    function generateDefaultJenisPerizinanFormat(): void;

}