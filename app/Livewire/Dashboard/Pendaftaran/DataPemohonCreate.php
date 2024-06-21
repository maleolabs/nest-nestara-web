<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Livewire\Forms\DataPemohonForm;
use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use App\utils\Helper\Modal;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DataPemohonCreate extends Component
{

    public DataPemohonForm $form_data_pemohon;

    public $allProvinsi = [],
        $allKabupaten = [],
        $allKecamatan = [],
        $allKelurahan = [],
        $pemohon_id;

    public $saved = false;

    private $modal;

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
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
        return view('livewire.dashboard.pendaftaran.data-pemohon-create', [
            'modal' => $this->modal
        ]);
    }

    private function setRegionData()
    {
        $this->allKabupaten = is_value_exists($this->form_data_pemohon->provinsi) ? $this->getRegionService()->getRegenciesByProvince($this->form_data_pemohon->provinsi) : [];
        $this->allKecamatan = is_value_exists($this->form_data_pemohon->kabupaten) ? $this->getRegionService()->getDistrictsByRegency($this->form_data_pemohon->kabupaten) : [];
        $this->allKelurahan = is_value_exists($this->form_data_pemohon->kecamatan) ? $this->getRegionService()->getVillagesByDistrict($this->form_data_pemohon->kecamatan) : [];
    }

    public function resetData()
    {
        $this->form_data_pemohon->clearValidation();
        $this->clearAllData();
    }

    public function save()
    {
        $this->validate();
        $saved_pemohon = null;
        if ($this->pemohon_id == null)
            try {
                $saved_pemohon = $this->getPemohonService()->save($this->form_data_pemohon->all());
            } catch (QueryException $e) {
                if ($e->getCode() == 23000)
                    throw ValidationException::withMessages([
                        'form_data_pemohon.nomor_id' => 'nomor id sudah terdaftar!'
                    ]);
            }
        else
            $saved_pemohon = $this->getPemohonService()->update($this->pemohon_id, $this->form_data_pemohon->all());
        $this->pemohon_id = $saved_pemohon->id;
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
        $this->modal = new Modal(false, true);
        $this->modal->setTitle('Hapus Data Pemohon');
        $this->modal->addConfirm('decision', 'Anda yakin ingin menghapus data pemohon ini?');
        $this->modal->setCancelButton('Tidak');
        $this->modal->setCancelFunction('dismissModal');
        $this->modal->setApplyButton('Ya, Hapus');
        $this->modal->setApplyButtonBgColor('bg-gradient-danger');
        $this->modal->setSubmitFunction('confirmDeleteData');
        $this->modal->show();
    }

    public function dismissModal()
    {
        $this->modal = null;
    }

    public function confirmDeleteData()
    {
        $this->modal = new Modal(false, true);
        try {
            $this->getPemohonService()->delete($this->pemohon_id);
        } catch (QueryException $e) {
            $this->modal->setTitle('Terjadi kesalahan!');
            $this->modal->setMessage($e->getMessage());
            $this->modal->setStatus(false);
            $this->modal->setCancelButton('Tutup');
            $this->modal->setCancelFunction('dismissModal');
            $this->modal->show();
            return;
        }
        $this->modal->setTitle('Sukses!');
        $this->modal->setMessage('Berhasil menghapus data pemohon!');
        $this->modal->setStatus(true);
        $this->modal->setCancelButton('Tutup');
        $this->modal->setCancelFunction('dismissModal');
        $this->modal->show();
    }

    private function getRegionValue($type, $id): string
    {
        $value = '';
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
        return $value;
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