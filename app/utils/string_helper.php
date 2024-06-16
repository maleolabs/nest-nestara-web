<?php 

if (!function_exists('str_capitalize')) {
    function str_capitalize($string): string
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
}

if (!function_exists('pretty_address')) {
    function pretty_address($address): string
    {
        $pretty_address = '';
        foreach ($address as $index => $addr) {
            if ($index > 0 && $pretty_address !== '')
                $pretty_address .= ', ';
            $pretty_address .= str_capitalize($addr);
        }
        return rtrim($pretty_address);
    }
}