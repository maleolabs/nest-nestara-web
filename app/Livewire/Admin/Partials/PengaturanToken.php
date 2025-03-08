<?php

namespace App\Livewire\Admin\Partials;

use App\Services\Security\Impl\ClientTokenService;
use App\Services\Security\TokenService;
use Carbon\Carbon;

trait PengaturanToken
{

    public $ocr_setting,
        $api_key,
        $base_url;

    public $ocr_config_saved = false;

    protected function setOcrApiConfig()
    {
        $this->api_key = get_pengaturan(OCR_API_KEY);
        $this->base_url = get_pengaturan(OCR_BASE_URL);
    }
    
    public function saveOcrApiConfig()
    {
        set_pengaturan(OCR_API_KEY, $this->api_key, OCR_API_SETT_GROUP);
        set_pengaturan(OCR_BASE_URL, $this->base_url, OCR_API_SETT_GROUP);

        $this->ocr_config_saved = true;
    }

}