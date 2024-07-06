<?php

namespace App\utils\enums;

class BackOfficeStatusEnum
{

    public const BELUM_ENTRI = 'belum di entri';

    public static function all(): array
    {
        return [
            self::BELUM_ENTRI,
        ];
    }

}