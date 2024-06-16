<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Services\APIClient\RegionAPIService;
use Livewire\Component;

class PendaftaranCreate extends Component
{
    public $provinsi,
        $kabupaten,
        $kecamatan,
        $kelurahan;
    
    public $allProvinsi = [],
        $allKabupaten = [],
        $allKecamatan = [],
        $allKelurahan = [];

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
        $this->provinsi = 75; // default for gorontalo province
    }

    public function render()
    {
        $this->allKabupaten = is_value_exists($this->provinsi) ? $this->getRegionService()->getRegenciesByProvince($this->provinsi) : [];
        $this->allKecamatan = is_value_exists($this->kabupaten) ? $this->getRegionService()->getDistrictsByRegency($this->kabupaten) : [];
        $this->allKelurahan = is_value_exists($this->kecamatan) ? $this->getResionService()->getVillagesByDistrict($this->kecamatan) : [];
        return view('livewire.dashboard.pendaftaran.pendaftaran-create');
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }

}