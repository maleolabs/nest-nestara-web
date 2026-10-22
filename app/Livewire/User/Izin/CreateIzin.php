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
use Illuminate\Validation\ValidationException;
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
        $filteredPerizinan = [],
        $selectedPerizinan,
        $persyaratan = [];

    public $jenis_izin_search = '';

    public function mount()
    {
        $this->allProvinsi = $this->getRegionService()->getProvinces();
        $this->form_data_pemohon->provinsi = 75;
    }

    public function updated($field)
    {
        $this->setUpdatedData($field);
        if ($field == 'data_izin_form.jenis_izin') {
            $this->applyJenisIzin();
        }
    }

    private function applyJenisIzin()
    {
        $this->selectedPerizinan = $this->getJenisPerizinanService()->findByName($this->data_izin_form->jenis_izin);
        $this->persyaratan = [];
    }

    public function selectJenisIzin(int $id)
    {
        $this->selectedPerizinan = $this->getJenisPerizinanService()->find($id);
        if (!$this->selectedPerizinan) {
            return;
        }
        $this->data_izin_form->jenis_izin = $this->selectedPerizinan->nama;
        $this->jenis_izin_search = $this->selectedPerizinan->nama;
        $this->persyaratan = [];
        $this->dispatch('izin-selected');
    }

    public function render()
    {
        $this->allPerizinan = JenisPerizinan::all();
        $q = trim($this->jenis_izin_search);
        $this->filteredPerizinan = $q === ''
            ? $this->allPerizinan
            : $this->allPerizinan->filter(fn ($p) => str_contains(strtolower($p->nama), strtolower($q)))->values();
        $this->setDataPemohonIfUserAlreadyHas();
        $this->setRegionData();
        return view('livewire.user.izin.create-izin');
    }

    public function save()
    {
        // validasi eksplisit agar kegagalan tidak terjadi diam-diam di step konfirmasi
        $this->data_izin_form->validate();
        if (!$this->selectedPerizinan) {
            throw ValidationException::withMessages([
                'data_izin_form.jenis_izin' => 'Jenis izin belum dipilih.',
            ]);
        }
        $this->saveDataPemohon(auth()->user()->id);
        $izin = $this->getPerizinanService()->saveRegistration($this->selectedPerizinan->id, $this->pemohon_id);
        $this->saveDataIzin($izin->id);
        // dispatch OCR setelah dokumen tersimpan + persist-first baris pending
        $this->getPerizinanService()->requestOcrForNewRegistration($izin);
        return redirect()->route('user.izin');
    }

    private function saveDataIzin($izin_id)
    {
        $data_persyaratan = $this->getFileService()->savePrivately($this->persyaratan);
        $this->getDokumenPemohonService()->saveAll($this->pemohon_id, $izin_id, $data_persyaratan);
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
        $this->form_step = max(self::FIRST_STEP, min(self::LAST_STEP, (int) $this->form_step));
        if ($this->form_step == self::FIRST_STEP)
            $this->form_data_pemohon->validate();
        elseif ($this->form_step == self::SECOND_STEP) {
            $this->data_izin_form->validate();
            if (!$this->selectedPerizinan) {
                throw ValidationException::withMessages(['data_izin_form.jenis_izin' => 'Jenis izin belum dipilih.']);
            }
            $syarat_izin_ids = $this->selectedPerizinan->syarat_izin->pluck('id')->all();
            $missing_syarat_ids = array_diff($syarat_izin_ids, array_keys($this->persyaratan));
            if (!empty($missing_syarat_ids)) {
                $missing_messages = [];
                foreach ($missing_syarat_ids as $missing_id) {
                    $syarat = $this->selectedPerizinan->syarat_izin->firstWhere('id', $missing_id);
                    $missing_messages["persyaratan.{$syarat->id}"] = "{$syarat->nama} diperlukan.";
                }
                throw ValidationException::withMessages($missing_messages);
            }
        }
        $this->form_step = min(self::LAST_STEP, $this->form_step + 1);
    }

    public function prevStep()
    {
        $this->form_step = max(self::FIRST_STEP, (int) $this->form_step - 1);
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
