<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Livewire\Forms\DataPemohonForm;
use App\Services\APIClient\RegionAPIService;
use GuzzleHttp\Exception\ConnectException;
use Livewire\Component;

class DataPemohonCreate extends Component
{

    public DataPemohonForm $form_data_pemohon;

    public $allProvinsi = [],
        $allKabupaten = [],
        $allKecamatan = [],
        $allKelurahan = [];

    public $saved = false;

    public function mount()
    {
        try {
            $this->allProvinsi = $this->getRegionService()->getProvinces();
        } catch (ConnectException $e) {

        }
        $this->form_data_pemohon->provinsi = 75; // default for gorontalo province
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
        $this->setRegionData();
        return view('livewire.dashboard.pendaftaran.data-pemohon-create');
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

    public function resetData()
    {
        $this->form_data_pemohon->clearValidation();
        $this->clearAllData();
    }

    public function save()
    {
        $this->validate();
        $this->saved = true;
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

    private function clearAllData()
    {
        $this->allKabupaten = [];
        $this->allKecamatan = [];
        $this->allKelurahan = [];
        $this->form_data_pemohon->sumber_identitas = null;
        $this->form_data_pemohon->nomor_id = null;
        $this->form_data_pemohon->nama_pemohon = null;
        $this->form_data_pemohon->no_telepon = null;
        $this->form_data_pemohon->provinsi = null;
        $this->form_data_pemohon->kabupaten = null;
        $this->form_data_pemohon->kecamatan = null;
        $this->form_data_pemohon->kelurahan = null;
        $this->form_data_pemohon->detail_alamat = null;
        $this->form_data_pemohon->alamat_luar_negeri = null;
    }

    public function deleteData()
    {
        
    }

    private function getRegionValue($type, $id): string
    {
        $value = '';
        try {
            switch ($type) {
                case 'provinsi':
                    $value = $this->getRegionService()->getProvince($id);
                    break;
                case 'kabupaten':
                    $value = $this->getRegionService()->getRegency($id);
                    break;
                case 'kecamatan':
                    $value = $this->getRegionService()->getDistrict($id);
                    break;
                case 'kelurahan':
                    $value = $this->getRegionService()->getVillage($id);
                    break;
            }
        } catch(ConnectException $e) {
            $value = 'Jaringan eror';
        }
        return $value;
    } 

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }
    
}