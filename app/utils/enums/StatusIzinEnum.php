<?php

namespace App\utils\enums;

class StatusIzinEnum
{

    public const DIDAFTARKAN = 'didaftarkan';
    public const PERUBAHAN = 'perubahan';
    public const PERPANJANGAN = 'perpanjangan';
    public const DITINJAU = 'ditinjau oleh petugas';
    public const PROSES = 'diproses';
    public const PENERBITAN = 'penerbitan izin';
    public const PENYERAHAN = 'penyerahan izin';
    public const BATAL = 'dibatalkan';

    public static function all(): array
    {
        return [
            self::DIDAFTARKAN,
            self::PERUBAHAN,
            self::PERPANJANGAN,
            self::DITINJAU,
            self::PROSES,
            self::PENERBITAN,
            self::PENYERAHAN,
            self::BATAL,
        ];
    }

}