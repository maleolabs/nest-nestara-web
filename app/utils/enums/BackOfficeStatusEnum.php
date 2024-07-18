<?php

namespace App\utils\enums;

/**
 * Enum yang merepresentasikan berbagai nilai status yang digunakan dalam sistem back office.
 */
class BackOfficeStatusEnum
{
    /**
     * Status ketika entri belum dibuat.
     */
    public const BELUM_ENTRI = 'belum di entri';

    /**
     * Status ketika entri sedang dibuat.
     */
    public const SEDANG_ENTRI = 'sedang di entri';

    /**
     * Status ketika entri sudah dibuat.
     */
    public const SUDAH_ENTRI = 'sudah di entri';

    /**
     * Status ketika entri sedang dibuat untuk tinjauan lapangan.
     */
    public const ENTRI_TINJAUAN_LAPANGAN = 'sedang di entri tinjauan lapangan';

    /**
     * Status ketika entri untuk tinjauan lapangan sudah dibuat.
     */
    public const SUDAH_ENTRI_TINJAUAN_LAPANGAN = 'sudah di entri tinjauan lapangan';

    /**
     * Status ketika terjadi perubahan pada entri, dan perlu dibuat kembali.
     */
    public const PERUBAHAN_ENTRI = 'terjadi perubahan data. mohon untuk entri data kembali!';

    /**
     * Status ketika permohonan izin direkomendasikan.
     */
    public const DIREKOM = 'direkomendasikan';

    /**
     * Status ketika permohonan izin tidak direkomendasikan.
     */
    public const TIDAK_DIREKOM = 'tidak direkomendasikan';

    /**
     * Status ketika permohonan izin direkomendasikan dengan catatan.
     */
    public const REKOM_CATATAN = 'direkomendasikan dengan catatan';

    /**
     * Status ketika permohonan izin memerlukan pembahasan lebih lanjut.
     */
    public const BAHAS_LANJUT = 'perlu pembahasan lebih lanjut';

    /***
    |------------------------------------------------------
    | Jenis kegiatan yang dilakukan oleh pegawai.
    |------------------------------------------------------
    | status ini tidak akan di kembalikan pada fungsi all()
    |
    */
    public const PERIKSA_BERKAS = 'pemeriksaan berkas';
    public const SURVEY_LAPANGAN = 'survey lapangan';
    public const RAPAT_TIM = 'rapat tim teknis';

    /**
     * Status ketika permohonan izin diizinkan.
     */
    public const DIIZINKAN = 'diizinkan';

    /**
     * Status ketika permohonan izin tidak diizinkan.
     */
    public const TIDAK_DIIZINKAN = 'tidak diizinkan';

    /**
     * Status ketika permohonan izin belum dicetak.
     */
    public const BELUM_DICETAK = 'belum dicetak';

    /**
     * Status ketika permohonan izin sudah dicetak.
     */
    public const DICETAK = 'dicetak';

    /**
     * Status ketika permohonan izin tidak dapat dilanjutkan.
     */
    public const LAYANAN_DITOLAK = 'layanan ditolak';

    /**
     * Status ketika permohonan izin sudah melebihi masa berlaku izin.
     */
    public const IZIN_DICABUT = 'izin dicabut';

    /**
     * Mengembalikan array dari semua nilai status.
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::BELUM_ENTRI,
            self::SEDANG_ENTRI,
            self::SUDAH_ENTRI,
            self::ENTRI_TINJAUAN_LAPANGAN,
            self::SUDAH_ENTRI_TINJAUAN_LAPANGAN,
            self::PERUBAHAN_ENTRI,
            self::DIREKOM,
            self::TIDAK_DIREKOM,
            self::REKOM_CATATAN,
            self::BAHAS_LANJUT,
        ];
    }
}