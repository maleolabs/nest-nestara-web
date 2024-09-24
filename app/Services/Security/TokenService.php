<?php

namespace App\Services\Security;

interface TokenService
{

    /**
     * Membuat token baru
     * 
     * @return void
     */
    function generateToken(): string;

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

}