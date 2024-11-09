<?php

namespace App\Livewire\Dashboard\Pendaftaran\Partials;

use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use Livewire\Component;

class PreviewPemohon extends Component
{

    use DataPemohonTrait;
    
    public $pemohon_id;

    public $pemohon;

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
        $this->form_data_pemohon->provinsi = 75; // default for gorontalo province
    }

    public function updated($field)
    {
        $this->setUpdatedData($field);
    }

    public function render()
    {
        $this->setUpDataPemohon();
        $this->setRegionData();

        return view('livewire.dashboard.pendaftaran.partials.preview-pemohon');
    }

    private function setUpDataPemohon()
    {
        $this->pemohon = $this->getPemohonService()->find($this->pemohon_id);
        $this->setDataPemohon($this->pemohon);
    }

    private function getPemohonService(): PemohonService
    {
        return app(PemohonService::class);
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }
    
}