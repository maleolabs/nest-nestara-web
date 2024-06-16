<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Livewire\Forms\DataPemohonForm;
use App\Services\APIClient\RegionAPIService;
use Livewire\Component;

class PendaftaranCreate extends Component
{
    public DataPemohonForm $form;
    
    public $allProvinsi = [],
        $allKabupaten = [],
        $allKecamatan = [],
        $allKelurahan = [];

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
        $this->form->provinsi = 75; // default for gorontalo province
    }

    public function updated($field)
    {
        if ($field == 'form.provinsi') {
            $this->form->kabupaten = null;
            $this->form->kecamatan = null;
            $this->form->kelurahan = null;
        } else if ($field == 'form.kabupaten') {
            $this->form->kecamatan = null;
            $this->form->kelurahan = null;
        } else if ($field == 'form.kecamatan') {
            $this->form->kelurahan = null;
        }
    }

    public function render()
    {
        $this->setRegionData();
        return view('livewire.dashboard.pendaftaran.pendaftaran-create');
    }

    private function setRegionData()
    {
        $this->allKabupaten = is_value_exists($this->form->provinsi) ? $this->getRegionService()->getRegenciesByProvince($this->form->provinsi) : [];
        $this->allKecamatan = is_value_exists($this->form->kabupaten) ? $this->getRegionService()->getDistrictsByRegency($this->form->kabupaten) : [];
        $this->allKelurahan = is_value_exists($this->form->kecamatan) ? $this->getRegionService()->getVillagesByDistrict($this->form->kecamatan) : [];
    }

    public function save()
    {
        $this->validate();
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }

}