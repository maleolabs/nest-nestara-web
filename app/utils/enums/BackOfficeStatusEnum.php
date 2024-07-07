<?php

namespace App\utils\enums;

class BackOfficeStatusEnum
{

    public const BELUM_ENTRI = 'belum di entri';
    public const SEDANG_ENTRI = 'sedang di entri';
    public const SUDAH_ENTRI = 'sudah di entri';

    public static function all(): array
    {
        return [
            self::BELUM_ENTRI,
            self::SEDANG_ENTRI,
            self::SUDAH_ENTRI,
        ];
    }

}