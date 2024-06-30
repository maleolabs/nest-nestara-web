<?php 

if (!function_exists('str_capitalize')) {
    function str_capitalize($string, $ls_remaining_char = false): string
    {
        if (is_string($string)) {
            $words = explode(" ", $string);
            $camelCase = '';
            foreach ($words as $word) {
                if ($ls_remaining_char) {
                    $first_char = strtoupper(substr($word, 0, 1));
                    $remaining_char = strtolower(substr($word, 1));
                    $proper_case_string = $first_char . $remaining_char;
                    $camelCase .= $proper_case_string . ' ';
                } else
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