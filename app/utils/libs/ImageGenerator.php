<?php

namespace App\utils\libs;

use App\Services\AppService\FileService;
use Illuminate\Support\Str;

class ImageGenerator
{

    public static function letterImage(string $letter, string $file_folder): string
    {
        $width = 50;
        $height = 50;

        $image = imagecreatetruecolor($width, $height);

        $randomBgColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));

        imagefill($image, 0, 0, $randomBgColor);

        $fontPath = public_path('assets/fonts/Poppins-Bold.otf');
        $fontSize = 20;
        $textColor = imagecolorallocate($image, 0, 0, 0);

        $bbox = imagettfbbox($fontSize, 0, $fontPath, $letter);
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2 + $textHeight;

        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $letter);

        $file_name = Str::random() . '.png';
        $file_path = $file_folder . '/' . $file_name;
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
        self::fileService()->save($file_path, $imageData);

        imagedestroy($image);

        return $file_name;
    }

    private static function fileService(): FileService
    {
        return app(FileService::class);
    }

}