<?php

namespace App\utils\enums;

class StatusIzinEnum
{

    public const DIDAFTARKAN = 'didaftarkan';

    public static function all(): array
    {
        return [
            self::DIDAFTARKAN,
        ];
    }

}