<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PegawaiService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SuratService;
use Illuminate\Validation\ValidationException;

trait TinjauanLapanganTrait
{

    public $preview_izin, 
        $preview_izin_id,
        $jenis_perizinan_id,
        $semua_penanda_tangan = [],
        $semua_tim_teknis = [],
        $no_surat,
        $clean_no_surat,
        $no_surat_format,
        $penandatangan,
        $tim_teknis = [];

    public function setTinjauanLapanganFormData()
    {
        $this->semua_penanda_tangan = $this->getPegawaiService()->getAllPenandaTangan();
        $this->semua_tim_teknis = $this->getPegawaiService()->getTimTeknis();

        if ($this->jenis_perizinan_id) {
            $this->no_surat = $this->getSuratService()->getNomor();
            $this->no_surat_format = $this->getSuratService()->getFormat($this->jenis_perizinan_id);            
        }
    }

    protected function getCleanNoSurat(): string
    {
        return str_pad($this->no_surat, 3, '0', STR_PAD_LEFT);
    }

    public function saveTinjauanLapangan()
    {
        $this->validateTinjauanLapanganForm();
        $nomor_surat = $this->getCleanNoSurat() . $this->no_surat_format;
        $this->getPerizinanService()->savePeninjauanLapangan($this->preview_izin_id, $this->penandatangan, $this->tim_teknis, $nomor_surat);
    }

    protected function hasTinjauanLapangan(): bool
    {
        $izin = $this->preview_izin;
        return $izin && $izin->no_surat && $izin->penandatangan_id && count($izin->timteknis) > 0;
    }

    private function validateTinjauanLapanganForm()
    {
        $this->validate([
            'no_surat' => ['required'],
            'penandatangan' => ['required']
        ]);
        if (count($this->tim_teknis) <= 0)
            throw ValidationException::withMessages([
                'tim_teknis' => 'Pilih tim teknis minimal 1!'
            ]);
    }

    protected function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    protected function getPegawaiService(): PegawaiService
    {
        return app(PegawaiService::class);
    }

    protected function getSuratService(): SuratService
    {
        return app(SuratService::class);
    }

}