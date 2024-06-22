<?php

namespace App\Livewire\User\Izin;

use App\Livewire\Dashboard\Pendaftaran\Partials\DataPemohonTrait;
use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use Livewire\Component;

class CreateIzin extends Component
{

    use DataPemohonTrait;

    public $pemohon_id;

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
        $this->form_data_pemohon->provinsi = 75;
    }

    public function updated($field)
    {
        $this->setUpdatedData($field);
    }

    public function render()
    {
        $this->setRegionData();
        return view('livewire.user.izin.create-izin');
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }

    private function getPemohonService(): PemohonService
    {
        return app(PemohonService::class);
    }
    
}