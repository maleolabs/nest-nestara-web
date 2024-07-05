<?php

namespace App\Livewire\Admin\Pendaftaran;

use App\Livewire\Forms\DataIzinForm;
use App\Livewire\Forms\DataPemohonForm;
use App\Models\JenisPerizinan;
use App\Services\APIClient\RegionAPIService;
use GuzzleHttp\Exception\ConnectException;
use Livewire\Component;

class DataIzinCreate extends Component
{

    public DataIzinForm $data_izin_form;
    public DataPemohonForm $form_data_pemohon;

    private const GORONTALO_ID = 75;

    public $allProvinsi = [],
        $allKabupaten = [],
        $allKecamatan = [],
        $allKelurahan = [];

    public $saved = false;

    public $allPerizinan = [];
    public $cari_pemohon;

    public function mount()
    {
        try {
            $this->allProvinsi = $this->getRegionService()->getProvinces();
        } catch (ConnectException $e) {

        }
        $this->form_data_pemohon->provinsi = self::GORONTALO_ID;
    }

    public function updated($field)
    {
        if ($field == 'form_data_pemohon.provinsi') {
            $this->form_data_pemohon->kabupaten = null;
            $this->form_data_pemohon->kecamatan = null;
            $this->form_data_pemohon->kelurahan = null;
        } else if ($field == 'form_data_pemohon.kabupaten') {
            $this->form_data_pemohon->kecamatan = null;
            $this->form_data_pemohon->kelurahan = null;
        } else if ($field == 'form_data_pemohon.kecamatan') {
            $this->form_data_pemohon->kelurahan = null;
        }
    }

    public function render()
    {
        $this->allPerizinan = JenisPerizinan::all();
        $this->setRegionData();
        return view('livewire.admin.pendaftaran.data-izin-create');
    }

    public function save(){
        $this->validate();
    }

    private function setRegionData()
    {
        try {
            $this->allKabupaten = is_value_exists($this->form_data_pemohon->provinsi) ? $this->getRegionService()->getRegenciesByProvince($this->form_data_pemohon->provinsi) : [];
            $this->allKecamatan = is_value_exists($this->form_data_pemohon->kabupaten) ? $this->getRegionService()->getDistrictsByRegency($this->form_data_pemohon->kabupaten) : [];
            $this->allKelurahan = is_value_exists($this->form_data_pemohon->kecamatan) ? $this->getRegionService()->getVillagesByDistrict($this->form_data_pemohon->kecamatan) : [];
        } catch (ConnectException $e) {

        }
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }
    
}