<?php

namespace App\Livewire\Admin\Partials;

use App\Services\Security\Impl\ClientTokenService;
use App\Services\Security\TokenService;
use Carbon\Carbon;

trait PengaturanToken
{

    public $ocr_setting,
        $api_token,
        $token_form_active = false,
        $token_expire_op = [],
        $nama_token,
        $token_expire = '1 Hari',
        $token_expired_at;

    public $token_used_date,
        $token_used_count;

    protected function setTokenData()
    {
        $this->api_token = get_pengaturan(ClientTokenService::SET_CLIENT_TOKEN);

        if ($token_expired_at = get_pengaturan(ClientTokenService::SET_CLIENT_TOKEN_TTL)) {
            $this->token_expired_at = Carbon::createFromTimestamp($token_expired_at)->translatedFormat('d F Y');
        }

        $this->token_used_count = get_pengaturan(ClientTokenService::SET_CLIENT_TOKEN_USED_COUNT);
    
        if ($token_used_date = get_pengaturan(ClientTokenService::SET_CLIENT_TOKEN_USED_DATE)) {
            $this->token_used_date = Carbon::createFromTimestamp($token_used_date)->diffForHumans();
        }
    }

    public function toggleTokenForm()
    {
        $this->token_form_active = !$this->token_form_active;
        if ($this->token_form_active) {
            $this->token_expire_op = $this->getExpireTokenOption();
        }
    }

    public function createToken()
    {
        $this->validate([
            'nama_token' => ['required'],
            'token_expire' => ['required']
        ]);

        $this->getTokenService()->create($this->nama_token, $this->token_expire_op[$this->token_expire]['type'], $this->token_expire_op[$this->token_expire]['ttl_long']);

        $this->toggleTokenForm();
    }

    public function regenerateToken()
    {
        $this->getTokenService()->regenerateToken();
    }

    public function deleteToken()
    {
        $this->getTokenService()->delete();
    }

    private function getExpireTokenOption(): array
    {
        return [
            '1 Hari' => [
                'ttl_long' => 1,
                'type' => 'day'
            ],
            '1 Bulan' => [
                'ttl_long' => 1,
                'type' => 'month'
            ],
            '3 Bulan' => [
                'ttl_long' => 3,
                'type' => 'month'
            ],
            '6 Bulan' => [
                'ttl_long' => 6,
                'type' => 'month'
            ],
            '1 Tahun' => [
                'ttl_long' => 1,
                'type' => 'year'
            ],
        ];
    }

    private function getTokenService(): TokenService
    {
        return app(TokenService::class);
    }

}