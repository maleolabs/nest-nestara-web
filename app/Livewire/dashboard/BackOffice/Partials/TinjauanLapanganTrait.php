<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PegawaiService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SuratService;
use App\utils\enums\BackOfficeStatusEnum;
use Illuminate\Validation\ValidationException;

trait TinjauanLapanganTrait
{

    public $preview_izin, 
        $preview_izin_id,
        $jenis_perizinan_id,
        $semua_penanda_tangan = [],
        $no_surat,
        $clean_no_surat,
        $no_surat_format,
        $penandatangan,
        $edit_tinjau_lapangan = false,
        $saved_nosurat,
        $saved_penandatangan;

    public function setTinjauanLapanganFormData()
    {
        $this->semua_penanda_tangan = $this->getPegawaiService()->getAllPenandaTangan();

        if ($this->jenis_perizinan_id) {
            if ($this->preview_izin->no_surat !== null && $this->preview_izin->penandatangan != null &&!$this->edit_tinjau_lapangan) {
                $this->saved_nosurat = $this->preview_izin->no_surat;
                $this->saved_penandatangan = $this->preview_izin->penandatangan->nama;

                $this->no_surat = $this->preview_izin->no_surat;
                $this->penandatangan = $this->preview_izin->penandatangan_id;
            } else {
                if (!$this->saved_nosurat)
                    $this->no_surat = str_pad($this->getSuratService()->getNomor(), 3, '0', STR_PAD_LEFT) . $this->getSuratService()->getFormat($this->jenis_perizinan_id);
                else
                    $this->no_surat = $this->saved_nosurat;
            }
        }
    }

    public function editTinjauData()
    {
        $this->edit_tinjau_lapangan = true;
        $this->setTinjauanLapanganFormData();
    }

    protected function getCleanNoSurat(): string
    {
        return str_pad($this->no_surat, 3, '0', STR_PAD_LEFT);
    }

    public function saveTinjauanLapangan()
    {
        $this->validateTinjauanLapanganForm();
        $nomor_surat = $this->getCleanNoSurat() . $this->no_surat_format;
        $this->getPerizinanService()->savePeninjauanLapangan($this->preview_izin_id, $this->penandatangan,$nomor_surat);
        return redirect()->route('pegawai.pendataan', [
            'tab' => 'Penjadwalan Tim Teknis',
            'preview_izin_id' => $this->preview_izin_id
        ]);
    }

    protected function hasTinjauanLapangan(): bool
    {
        $izin = $this->preview_izin;
        return $izin && $izin->no_surat && $izin->penandatangan_id;
    }

    protected function canEditTinjauanLapangan(): bool
    {
        $editable_status = [
            BackOfficeStatusEnum::SUDAH_ENTRI_TINJAUAN_LAPANGAN
        ];
        return in_array($this->last_izin_status->keterangan, $editable_status);
    }

    private function validateTinjauanLapanganForm()
    {
        $this->validate([
            'no_surat' => ['required'],
            'penandatangan' => ['required']
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