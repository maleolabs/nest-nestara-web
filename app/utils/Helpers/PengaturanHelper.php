<?php

use App\Services\DomainServices\PengaturanService;

if (!function_exists('get_pengaturan')) {
    function get_pengaturan(string $nama, $default = null, $user_id = null)
    {
        $pengaturanService = app(PengaturanService::class);
        $pengaturan = $pengaturanService->find($nama, $user_id);
        return $pengaturan ? $pengaturan->value : $default;
    }
}

if (!function_exists('set_pengaturan')) {
    function set_pengaturan(string $nama, $value, string $group = null, $user_id = null)
    {
        $pengaturanService = app(PengaturanService::class);
        return $pengaturanService->updateOrCreate($nama, $value, $group, $user_id);
    }
}