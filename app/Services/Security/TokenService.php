<?php

namespace App\Services\Security;

interface TokenService
{

    /**
     * Membuat token baru dengan data token yang kustom
     * 
     * @param string $data Data atau nama token
     * @param string $ttl_type Tipe waktu expire (day, month, year)
     * @param int $ttl_long Lama waktu expire
     * 
     * @return void
     */
    function create(string $data, string $ttl_type, int $ttl_long): void;

    /**
     * Membuat token baru
     * 
     * @return string
     */
    function generateToken(): string;

    /**
     * Menghasilkan kembali token
     * 
     * @param int|null $ttl
     * @return void
     */
    function regenerateToken(?string $ttl_type = null, ?int $ttl_long = null): void;

    /**
     * Memverifikasi token
     * 
     * @param string $token Token yang akan diverifikasi
     * @return bool
     */
    function verifyToken(string $token): bool;

    /**
     * Mengambil token
     * 
     * @return string|null Token
     */
    function getToken(): ?string;

    /**
     * Menghapus token
     * 
     * @return void
     */
    function delete(): void;

}