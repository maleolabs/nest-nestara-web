<?php

namespace App\utils\enums;

class StatusIzinEnum
{

    public const DIDAFTARKAN = 'didaftarkan'; // Status ketika permohonan izin telah didaftarkan
    public const PERUBAHAN = 'perubahan'; // Status ketika ada perubahan pada permohonan izin
    public const PERPANJANGAN = 'perpanjangan'; // Status ketika permohonan izin adalah untuk perpanjangan
    public const DITINJAU = 'ditinjau oleh petugas'; // Status ketika permohonan izin sedang ditinjau oleh petugas
    public const REVISI_PERSYARATAN = 'revisi persyaratan'; // Status ketika permohonan izin memerlukan revisi persyaratan
    public const PROSES = 'diproses'; // Status ketika permohonan izin sedang diproses
    public const PENERBITAN = 'penerbitan izin'; // Status ketika izin sedang dalam tahap penerbitan
    public const PENYERAHAN = 'penyerahan izin'; // Status ketika izin siap untuk diserahkan
    public const BATAL = 'dibatalkan'; // Status ketika permohonan izin dibatalkan


    /**
     * Mendapatkan semua status izin
     * @return array
     */
    public static function all(): array
    {
        return [
            self::DIDAFTARKAN,
            self::PERUBAHAN,
            self::PERPANJANGAN,
            self::DITINJAU,
            self::REVISI_PERSYARATAN,
            self::PROSES,
            self::PENERBITAN,
            self::PENYERAHAN,
            self::BATAL,
        ];
    }

}