<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Livewire\Admin\Pendaftaran\Partials\DataPemohonTrait;
use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use Illuminate\Database\QueryException;
use Livewire\Component;

class DataPemohonCreate extends Component
{

    use DataPemohonTrait;

    public $pemohon_id;

    public $saved = false;

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
        $this->setRegionData();
        return view('livewire.admin.pendaftaran.data-pemohon-create');
    }

    public function resetData()
    {
        $this->form_data_pemohon->clearValidation();
        $this->clearAllData();
    }

    public function editData()
    {
        $this->saved = false;
    }

    public function addNew()
    {
        $this->saved = false;
        $this->clearAllData();
    }

    public function deleteData()
    {
        try {
            $this->getPemohonService()->delete($this->pemohon_id);
        } catch (QueryException $e) {
            return;
        }
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