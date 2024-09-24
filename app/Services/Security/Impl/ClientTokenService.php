<?php

namespace App\Services\Security\Impl;

use App\Services\Security\TokenService;
use Carbon\Carbon;

class ClientTokenService implements TokenService
{

    private const CLIENT_TOKEN_DATA = 'ocr-request-token';
    private const SET_CLIENT_TOKEN = 'CLIENT_TOKEN';
    private const SET_CLIENT_TOKEN_TTL = 'CLIENT_TOKEN_TTL';
    private const SET_SECURITY_GROUP = 'SECURITY';

    private $secretKey;

    public function __construct()
    {
        $this->secretKey = config('jwt.secret');
    }

    public function generateToken(): string
    {
        $current_timestamp = now()->timestamp;

        $token_ttl = Carbon::createFromTimestamp($current_timestamp)->addDays(7)->timestamp;
        $token = $this->createHmacToken($token_ttl);

        set_pengaturan(self::SET_CLIENT_TOKEN, $token, self::SET_SECURITY_GROUP);
        set_pengaturan(self::SET_CLIENT_TOKEN_TTL, $token_ttl, self::SET_SECURITY_GROUP);

        return $token;
    }

    public function verifyToken(string $token): bool
    {
        $token_ttl = get_pengaturan(self::SET_CLIENT_TOKEN_TTL);

        if (is_null($token) || is_null($token_ttl)) return false;
        
        if (Carbon::createFromTimestamp($token_ttl)->isBefore(now())) return false;

        $expected_token = $this->createHmacToken($token_ttl);
        if ($token !== $expected_token) return false;

        return true;
    }

    public function getToken(): ?string
    {
        return get_pengaturan(self::SET_CLIENT_TOKEN);
    }

    private function createHmacToken($timestamp): string
    {
        $tokenData = $timestamp . '-' . self::CLIENT_TOKEN_DATA;
        $token = hash_hmac('sha256', $tokenData, $this->secretKey);
        return $token;
    }

}