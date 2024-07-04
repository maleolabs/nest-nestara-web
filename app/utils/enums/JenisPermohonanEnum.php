<?php 

namespace App\utils\enums;

class JenisPermohonanEnum
{

    public const BARU = 'baru';
    public const PERPANJANGAN = 'perpanjangan';
    public const PERUBAHAN = 'perubahan';

    public static function all(): array
    {
        return [
            self::BARU,
            self::PERPANJANGAN,
            self::PERUBAHAN,
        ];
    }

}