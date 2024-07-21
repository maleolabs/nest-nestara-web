<?php

use App\Services\APIClient\RegionAPIService;
use App\Services\AppService\EncryptionService;

if (!function_exists('str_capitalize')) {
    function str_capitalize($string, $ls_remaining_char = false): string
    {
        if (is_string($string)) {
            $words = explode(" ", $string);
            $camelCase = '';
            $bracket_pos = strpos($string, '(');

            foreach ($words as $word) {
                if ($bracket_pos !== false && strpos($word, '(') !== false) {
                    $camelCase .= strtoupper($word) . ' ';
                } else {
                    if ($ls_remaining_char) {
                        $first_char = strtoupper(substr($word, 0, 1));
                        $remaining_char = strtolower(substr($word, 1));
                        $proper_case_string = $first_char . $remaining_char;
                        $camelCase .= $proper_case_string . ' ';
                    } else
                        $camelCase .= ucfirst($word) . ' ';
                }
            }

            if ($bracket_pos) {
                $bracket_words = strtoupper(substr($camelCase, $bracket_pos));
                // TODO: menimpa kata di dalam kurung pada variabel $camelCase 
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

if (!function_exists('convert_address')) {
    function convert_address($address): string
    {
        $regionService = app(RegionAPIService::class);
        $addressParts = explode(',', $address);
        $convertedParts = [];

        foreach ($addressParts as $index => $part) {
            $part = trim($part);
            if (is_numeric($part) && ctype_digit($part)) {
                $partId = (int) $part;
                $partValue = match ($index) {
                    1 => 'desa/kelurahan ' . $regionService->getVillage($partId),
                    2 => 'kecamatan ' . $regionService->getDistrict($partId),
                    3 => 'kabupaten/kota ' . $regionService->getRegency($partId),
                    4 => 'provinsi ' . $regionService->getProvince($partId),
                    default => '',
                };
                $convertedParts[] = $partValue ? str_capitalize($partValue, true) : '';
            } else {
                $convertedParts[] = str_capitalize($part, true);
            }
        }

        return implode(', ', array_filter($convertedParts));
    }
}

if (!function_exists('isValidNameWithTitle')) {
    function isValidNameWithTitle($name): bool
    {
        if (!is_string($name) || empty($name))
            return false;

        $name = strtolower(trim($name));

        $titles = [
            // Gelar Akademik Umum
            'Dr', 'Prof', 'Mr', 'Mrs', 'Ms', 'Miss', 'Sir', 'Madam', 'Mx',

            // Gelar Militer
            'Capt', 'Col', 'Maj', 'Sgt', 'Lt', 'Gen', 'Cmdr',

            // Gelar Profesional
            'Eng', 'Ir', 'Ar', 'Arch', 'CPA', 'CA', 'Esq',

            // Gelar Akademik (Indonesia)
            'AP', 'S.E', 'S.Kom', 'S.T', 'S.H', 'S.Pd', 'S.Si', 'S.Farm', 'S.Kep', 'S.KG', 'S.IP',
            'S.Psi', 'S.Sos', 'S.E.I', 'S.Ag', 'S.Th', 'S.Ars', 'S.Sn', 'S.Pt', 'S.Hut', 'S.KH', 'S.Pt',
            'S.Pt', 'S.Mat', 'S.Stat', 'S.Han', 'S.PKP', 'S.Adm.Neg', 'S.Adm.Bis', 'S.Kes', 'S.Gz',
            'S.KPM', 'S.Tr.Kes', 'S.Tr.T', 'S.Tr.Pi', 'S.Tr.S.T', 'S.Tr.M', 'S.Tr.Ak', 'S.Tr.Kom',
            'M.Kom', 'MCE',

            // Gelar Akademik (Luar Negeri)
            'B.A', 'B.Sc', 'B.Eng', 'B.Arch', 'LL.B', 'M.A', 'M.Sc', 'M.Eng', 'M.Arch', 'LL.M',
            'PhD', 'D.Sc', 'M.D', 'D.M.D', 'D.D.S', 'M.P.H', 'M.P.A', 'M.B.A', 'M.Fin', 'J.D', 'M.Div',
            'Th.M', 'Th.D'
        ];

        $titlePattern = implode('|', array_map('preg_quote', $titles));

        $titlePattern = implode('|', array_map('strtolower', $titles));

        $pattern = "/^(($titlePattern)\\.? )*[a-zA-Z\\s]+((\\.?,? ($titlePattern)\\.?)+)$/";

        if (!preg_match($pattern, $name))
            return false;

        return true;
    }
}

if (!function_exists('encrypt_id')) {
    function encrypt_id($id): string
    {
        return app(EncryptionService::class)->encryptId($id);
    }
}