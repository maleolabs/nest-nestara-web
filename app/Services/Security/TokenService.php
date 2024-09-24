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
     * @return bool
     */
    function verifyToken(): bool;

    /**
     * Mengambil token
     * 
     * @return string|null Token
     */
    function getToken(): ?string;

}