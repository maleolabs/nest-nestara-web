<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

trait TinjauanLapanganTrait
{

    public $semua_penanda_tangan = [],
        $semua_tim_teknis = [];

    public function setTinjauanLapanganFormData()
    {
        $this->semua_penanda_tangan = [

        ];

        $this->semua_tim_teknis = [

        ];
    }

}