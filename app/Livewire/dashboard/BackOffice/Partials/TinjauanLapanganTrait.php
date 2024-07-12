<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PegawaiService;
use Illuminate\Validation\ValidationException;

trait TinjauanLapanganTrait
{

    public $semua_penanda_tangan = [],
        $semua_tim_teknis = [],
        $no_surat,
        $penandatangan,
        $tim_teknis = [];

    public function setTinjauanLapanganFormData()
    {
        $this->semua_penanda_tangan = $this->getPegawaiService()->getAllPenandaTangan();
        $this->semua_tim_teknis = $this->getPegawaiService()->getTimTeknis();
    }

    public function saveTinjauanLapangan()
    {
        $this->validateTinjauanLapanganForm();
        dd($this->no_surat, $this->penandatangan, $this->tim_teknis);
    }

    private function validateTinjauanLapanganForm()
    {
        $this->validate([
            'no_surat' => ['required', 'string'],
            'penandatangan' => ['required']
        ]);
        if (count($this->tim_teknis) <= 0)
            throw ValidationException::withMessages([
                'tim_teknis' => 'Pilih tim teknis minimal 1!'
            ]);
    }

    protected function getPegawaiService(): PegawaiService
    {
        return app(PegawaiService::class);
    }

}