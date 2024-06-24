<?php 

namespace App\Livewire\Dashboard\Pendaftaran\Partials;

use App\Livewire\Forms\DataPemohonForm;
use App\Models\Pemohon;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

trait DataPemohonTrait
{

    public DataPemohonForm $form_data_pemohon;

    public $allProvinsi = [],
        $allKabupaten = [],
        $allKecamatan = [],
        $allKelurahan = [];

    protected function setUpdatedData($field)
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

    protected function setRegionData()
    {
        $this->allKabupaten = is_value_exists($this->form_data_pemohon->provinsi) ? $this->getRegionService()->getRegenciesByProvince($this->form_data_pemohon->provinsi) : [];
        $this->allKecamatan = is_value_exists($this->form_data_pemohon->kabupaten) ? $this->getRegionService()->getDistrictsByRegency($this->form_data_pemohon->kabupaten) : [];
        $this->allKelurahan = is_value_exists($this->form_data_pemohon->kecamatan) ? $this->getRegionService()->getVillagesByDistrict($this->form_data_pemohon->kecamatan) : [];
    }

    protected function clearAllData()
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

    public function saveDataPemohon($user_id = null)
    {
        $this->validate();
        $saved_pemohon = null;
        if ($this->pemohon_id == null)
            try {
                $saved_pemohon = $this->getPemohonService()->save($this->form_data_pemohon->all(), $user_id);
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

    protected function setDataPemohon(Pemohon $pemohon)
    {
        $this->form_data_pemohon->sumber_identitas = $pemohon->sumber_identitas;
        $this->form_data_pemohon->nomor_id = $pemohon->nomor_id;
        $this->form_data_pemohon->nama_pemohon = $pemohon->nama_pemohon;
        $this->form_data_pemohon->no_telepon = $pemohon->no_telepon;
        $this->form_data_pemohon->provinsi = $pemohon->provinsi;
        $this->form_data_pemohon->kabupaten = $pemohon->kabupaten;
        $this->form_data_pemohon->kecamatan = $pemohon->kecamatan;
        $this->form_data_pemohon->kelurahan = $pemohon->kelurahan;
        $this->form_data_pemohon->detail_alamat = $pemohon->detail_alamat;
        $this->form_data_pemohon->alamat_luar_negeri = $pemohon->alamat_luar_negeri;
    }

    protected function getRegionValue($type, $id): string
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

}