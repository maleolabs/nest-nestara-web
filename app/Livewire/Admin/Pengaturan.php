<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\KopSuratForm;
use App\Services\DomainServices\SuratService;
use Livewire\Component;

class Pengaturan extends Component
{

    public KopSuratForm $kop_surat_form;

    public $nomor_surat,
        $tempat_penetapan;

    public function render()
    {
        $this->setUpSuratForm();

        return view('livewire.admin.pengaturan');
    }

    private function setUpSuratForm()
    {
        $kop_surat = $this->getSuratService()->getKopSurat();
        $nomor_surat = $this->getSuratService()->getLastNomor();

        $this->kop_surat_form->nama_pemerintah = $kop_surat['nama_pemerintah'] ?? null;
        $this->kop_surat_form->nama_dinas = $kop_surat['nama_dinas'] ?? null;
        $this->kop_surat_form->alamat = $kop_surat['alamat'] ?? null;
        $this->kop_surat_form->website = $kop_surat['website'] ?? null;

        $this->tempat_penetapan = $kop_surat['tempat_penetapan'] ?? null;

        $this->nomor_surat = $nomor_surat;
    }

    public function saveKopSurat()
    {
        $this->kop_surat_form->validate();

        $this->getSuratService()->saveKopSurat($this->kop_surat_form->all());
    }

    public function saveInfoSurat()
    {
        $this->validate([
            'nomor_surat' => ['required', 'min:0'],
            'tempat_penetapan' => ['required', 'string']
        ]);

        $this->getSuratService()->saveNomor($this->nomor_surat);
        $this->getSuratService()->saveTempatPenetapan($this->tempat_penetapan);
    }

    private function getSuratService(): SuratService
    {
        return app(SuratService::class);
    }
    
}