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