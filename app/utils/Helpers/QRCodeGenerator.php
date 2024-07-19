<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\RoundBlockSizeMode;

if (!function_exists('generate_qrcode_as_uri')) {
    function generate_qrcode_as_uri(string $text, int $size = 150): string
    {
        $qrcode = Builder::create()
                ->data($text)
                ->size($size)
                ->margin(0)
                ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                ->logoPath(public_path('storage/pdf-assets/cop-icon.png'))
                ->logoResizeToWidth(35)
                ->build();
        return $qrcode->getDataUri();
    }
}