<?php

namespace App\utils\enums;

class StatusIzinProgressEnum
{

    public const UNPROCESSED = 'belum diproses';
    public const CANCELED = 'tidak dilanjutkan';
    public const ERROR = 'terjadi kesalahan';
    public const PROCESSING = 'sementara diproses';
    public const FINISHED = 'selesai';

    public static function all(): array
    {
        return [
            self::UNPROCESSED,
            self::CANCELED,
            self::ERROR,
            self::PROCESSING,
            self::FINISHED
        ];
    }

}