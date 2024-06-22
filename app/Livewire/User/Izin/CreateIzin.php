<?php

namespace App\Livewire\User\Izin;

use App\Livewire\Dashboard\Pendaftaran\Partials\DataPemohonTrait;
use App\Livewire\Forms\DataIzinForm;
use App\Models\JenisPerizinan;
use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use Livewire\Component;

class CreateIzin extends Component
{

    use DataPemohonTrait;

    public DataIzinForm $data_izin_form;

    public $pemohon_id;

    public $form_step = 1;

    private const FIRST_STEP = 1;
    private const SECOND_STEP = 2;
    private const LAST_STEP = 3;

    public $allPerizinan = [];

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
        $this->allPerizinan = JenisPerizinan::all();
        $this->setRegionData();
        return view('livewire.user.izin.create-izin');
    }

    public function nextStep()
    {
        if ($this->form_step == self::FIRST_STEP)
            $this->form_data_pemohon->validate();
        $this->form_step++;
    }

    public function prevStep()
    {
        $this->form_step--;
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