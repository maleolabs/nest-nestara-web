<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PegawaiService;

trait TinjauanLapanganTrait
{

    public $semua_penanda_tangan = [],
        $semua_tim_teknis = [];

    public function setTinjauanLapanganFormData()
    {
        $this->semua_penanda_tangan = $this->getPegawaiService()->getAllPenandaTangan();
        $this->semua_tim_teknis = $this->getPegawaiService()->getTimTeknis();
    }

    protected function getPegawaiService(): PegawaiService
    {
        return app(PegawaiService::class);
    }

}