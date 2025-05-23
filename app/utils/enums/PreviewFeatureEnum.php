<?php

namespace App\utils\enums;

class PreviewFeatureEnum
{

    public const ENTRI_DATA = 'entri-data';
    public const TINJAUAN_LAPANGAN = 'tinjuan-lapangan';
    public const TINJAUAN_PERMOHONAN = 'tinjauan-permohonan';

    public static function all(): array
    {
        return [
            self::ENTRI_DATA,
            self::TINJAUAN_LAPANGAN,
            self::TINJAUAN_PERMOHONAN
        ];
    }

}