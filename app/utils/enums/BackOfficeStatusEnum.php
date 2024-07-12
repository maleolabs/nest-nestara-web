<?php

namespace App\utils\enums;

class BackOfficeStatusEnum
{

    public const BELUM_ENTRI = 'belum di entri';
    public const SEDANG_ENTRI = 'sedang di entri';
    public const SUDAH_ENTRI = 'sudah di entri';
    public const ENTRI_TINJAUAN_LAPANGAN = 'sedang di entri tinjauan lapangan';
    public const SUDAH_ENTRI_TINJAUAN_LAPANGAN ='sudah di entri tinjauan lapangan';
    public const PERUBAHAN_ENTRI = 'terjadi perubahan data. mohon untuk entri data kembali!';

    public static function all(): array
    {
        return [
            self::BELUM_ENTRI,
            self::SEDANG_ENTRI,
            self::SUDAH_ENTRI,
            self::ENTRI_TINJAUAN_LAPANGAN,
            self::SUDAH_ENTRI_TINJAUAN_LAPANGAN,
            self::PERUBAHAN_ENTRI
        ];
    }

}