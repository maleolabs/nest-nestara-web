<?php

namespace App\Services\Security\Impl;

use App\Exceptions\TokenExpireException;
use App\Exceptions\TokenMismatchException;
use App\Services\DomainServices\PengaturanService;
use App\Services\Security\TokenService;
use Carbon\Carbon;

class ClientTokenService implements TokenService
{

    private const DEFAULT_TOKEN_DATA = 'ocr-api-client-token';
    private const SET_CLIENT_TOKEN_DATA = 'CLIENT_TOKEN_DATA';
    private const SET_CLIENT_TOKEN = 'CLIENT_TOKEN';
    private const SET_CLIENT_TOKEN_TTL = 'CLIENT_TOKEN_TTL';
    private const SET_SECURITY_GROUP = 'SECURITY';

    private $secretKey;

    private PengaturanService $pengaturanService;

    public function __construct(PengaturanService $pengaturanService)
    {
        $this->pengaturanService = $pengaturanService;
        $this->secretKey = config('app.key');
    }

    public function create(string $data, string $ttl_type, int $ttl_long): void
    {
        $token_ttl = $this->getTokenTTL($ttl_type, $ttl_long);
        $token = $this->createHmacToken($token_ttl, $data);

        $this->saveToken($data, $token, $token_ttl);
    }

    public function generateToken(): string
    {
        $current_timestamp = now()->timestamp;

        $token_ttl = Carbon::createFromTimestamp($current_timestamp)->addDays(7)->timestamp;
        $token = $this->createHmacToken($token_ttl, self::DEFAULT_TOKEN_DATA);

        $this->saveToken(self::DEFAULT_TOKEN_DATA, $token, $token_ttl);

        return $token;
    }

    public function verifyToken(string $token): bool
    {
        $token_ttl = get_pengaturan(self::SET_CLIENT_TOKEN_TTL);

        if (is_null($token) || is_null($token_ttl)) return false;
        
        $token_expires_at = Carbon::createFromTimestamp($token_ttl);
        if ($token_expires_at->isBefore(now())) {
            throw new TokenExpireException('token expires at ' . $token_expires_at->translatedFormat('d-m-Y H:i'));
        }

        $expected_token = $this->createHmacToken($token_ttl);
        if ($token !== $expected_token) {
            throw new TokenMismatchException();
        }

        return true;
    }

    public function getToken(): ?string
    {
        return get_pengaturan(self::SET_CLIENT_TOKEN);
    }

    public function delete(): void
    {
        $this->pengaturanService->deleteByNama(self::SET_CLIENT_TOKEN);
        $this->pengaturanService->deleteByNama(self::SET_CLIENT_TOKEN_TTL);
        $this->pengaturanService->deleteByNama(self::SET_CLIENT_TOKEN_DATA);
    }

    private function createHmacToken($timestamp, $data = null): string
    {
        $data = $data ?? get_pengaturan(self::SET_CLIENT_TOKEN_DATA);
        $tokenData = $timestamp . '-' . $data;
        $token = hash_hmac('sha256', $tokenData, $this->secretKey);
        return $token;
    }

    private function saveToken($data, $token, $ttl)
    {
        set_pengaturan(self::SET_CLIENT_TOKEN_DATA, $data, self::SET_SECURITY_GROUP);
        set_pengaturan(self::SET_CLIENT_TOKEN, $token, self::SET_SECURITY_GROUP);
        set_pengaturan(self::SET_CLIENT_TOKEN_TTL, $ttl, self::SET_SECURITY_GROUP);
    }

    private function getTokenTTL(string $ttl_type, int $ttl_long)
    {
        $curr_time = Carbon::now();
        switch ($ttl_type) {
            case 'day':
                $curr_time->addDays($ttl_long);
                break;

            case 'month':
                $curr_time->addMonths($ttl_long);
                break;

            case 'year':
                $curr_time->addYears($ttl_long);
                break;
            
            default:
                $curr_time->addDay();
                break;
        }
        return $curr_time->timestamp;
    }

}