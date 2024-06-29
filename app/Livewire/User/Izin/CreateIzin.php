<?php

namespace App\Livewire\User\Izin;

use App\Livewire\Dashboard\Pendaftaran\Partials\DataPemohonTrait;
use App\Livewire\Forms\DataIzinForm;
use App\Models\JenisPerizinan;
use App\Services\APIClient\RegionAPIService;
use App\Services\AppService\FileService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateIzin extends Component
{

    use DataPemohonTrait, WithFileUploads;

    public DataIzinForm $data_izin_form;

    public $pemohon_id;

    public $form_step = 1,
        $user_already_has_data_pemohon = false,
        $edit_existing_data_pemohon = false;

    private const FIRST_STEP = 1;
    private const SECOND_STEP = 2;
    private const LAST_STEP = 3;

    public $allPerizinan = [],
        $selectedPerizinan,
        $persyaratan = [];

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
        $this->form_data_pemohon->provinsi = 75;
    }

    public function updated($field)
    {
        $this->setUpdatedData($field);
        if ($field == 'data_izin_form.jenis_izin') {
            $this->selectedPerizinan = $this->getJenisPerizinanService()->findByName($this->data_izin_form->jenis_izin);
        }
    }

    public function render()
    {
        $this->allPerizinan = JenisPerizinan::all();
        $this->setDataPemohonIfUserAlreadyHas();
        $this->setRegionData();
        return view('livewire.user.izin.create-izin');
    }

    public function save()
    {
        $this->saveDataPemohon(auth()->user()->id);
        $this->saveDataIzin();
        $this->getPerizinanService()->save($this->selectedPerizinan->id, $this->pemohon_id);
        Session::push('data-izin-saved', 'Anda telah berhasil mendaftar ' . $this->selectedPerizinan->nama);
        return redirect()->route('user.izin');
    }

    private function saveDataIzin()
    {
        $data_persyaratan = $this->getFileService()->savePrivately($this->persyaratan);
        $this->getDokumenPemohonService()->saveAll($this->pemohon_id, $data_persyaratan);
    }

    public function editExistingDataPemohon()
    {
        $this->edit_existing_data_pemohon = !$this->edit_existing_data_pemohon;
    }

    public function saveChangeDataPemohon()
    {
        $this->form_data_pemohon->validate();
        $this->saveDataPemohon(auth()->user()->id);
        $this->edit_existing_data_pemohon = false;
    }

    private function setDataPemohonIfUserAlreadyHas()
    {
        $pemohon = auth()->user()->pemohon;
        if ($pemohon != null) {
            $this->setDataPemohon($pemohon);
            $this->pemohon_id = $pemohon->id;
            $this->user_already_has_data_pemohon = true;
        }
    }

    protected function getAlamat()
    {
        return "{$this->form_data_pemohon->detail_alamat}, " . $this->getRegionValue('kelurahan', $this->form_data_pemohon->kelurahan) . ', ' . $this->getRegionValue('kecamatan', $this->form_data_pemohon->kecamatan) . ', ' . $this->getRegionValue('kabupaten', $this->form_data_pemohon->kabupaten) . ', ' . $this->getRegionValue('provinsi', $this->form_data_pemohon->provinsi);
    }

    public function nextStep()
    {
        if ($this->form_step == self::FIRST_STEP)
            $this->form_data_pemohon->validate();
        elseif ($this->form_step == self::SECOND_STEP)
            $this->data_izin_form->validate();
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

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }

    private function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }

    private function getFileService(): FileService
    {
        return app(FileService::class);
    }

    private function getDokumenPemohonService(): DokumenPemohonService
    {
        return app(DokumenPemohonService::class);
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}