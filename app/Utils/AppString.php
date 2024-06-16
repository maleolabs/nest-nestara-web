<?php 

namespace App\Utils;

class AppString
{

    public static function capitalize($string): string
    {
        if (is_string($string)) {
            $words = explode(" ", $string);
            $camelCase = '';
            foreach ($words as $word) {
                $camelCase .= ucfirst($word) . ' ';
            }
            $camelCase = rtrim($camelCase);
            return $camelCase;
        }
    }

    public static function prettyAddress($address): string
    {
        $pretty_address = '';
        foreach ($address as $index => $addr) {
            if ($index > 0 && $pretty_address !== '')
                $pretty_address .= ', ';
            $pretty_address .= self::capitalize($addr);
        }
        return rtrim($pretty_address);
    }

}