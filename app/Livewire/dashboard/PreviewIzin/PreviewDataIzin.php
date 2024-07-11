<?php

namespace App\Livewire\Dashboard\PreviewIzin;

use App\Livewire\Dashboard\BackOffice\Partials\EntriDataTrait;
use App\Livewire\Dashboard\BackOffice\Partials\TinjauanLapanganTrait;
use App\Livewire\Dashboard\PreviewIzin\Partials\HasPermissionToAccess;
use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\PreviewFeatureEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewDataIzin extends Component
{

    use EntriDataTrait, TinjauanLapanganTrait, HasPermissionToAccess;

    public $preview_izin_id,
        $preview_izin,
        $preview_izin_dokumen = [],
        $preview_izin_syarat_izin = [],
        $last_izin_status,
        $jenis_perizinan_id;

    public $modalImgTitle,
        $modalImgSource,
        $modalPdfSource;

    public $tanggal_peninjauan,
        $lokasi_izin,
        $keterangan,
        $action_confirm,
        $editing_data_action = false;

    public $active_tab;
    private const TINDAKAN = 'tindakan';
    private const PERSYARATAN = 'persyaratan';
    
    public function mount()
    {
        if ($this->hasPermission(PreviewFeatureEnum::ENTRI_DATA)) {
            $this->is_entri_changed = $this->last_izin_status && $this->last_izin_status->keterangan == BackOfficeStatusEnum::PERUBAHAN_ENTRI;
            $this->getPerizinanService()->updateIzinStatusWhenEntriDataHasChanged($this->preview_izin_id);
        }
        if ($this->hasPermission(PreviewFeatureEnum::TINJAUAN_LAPANGAN)) {
            $this->setTinjauanLapanganFormData();
        }
        if ($this->hasPermission(PreviewFeatureEnum::TINJAUAN_PERMOHONAN)) {
            $this->action_confirm = 'permohonan-selesai';
        }
        $this->setDataPreview();
        $this->dispatch('component-rendered');
    }

    public function updated($field)
    {
        $action_data = [
            'tanggal_peninjauan',
            'lokasi_izin',
            'keterangan',
            'action_confirm',
        ];
        if (in_array($field, $action_data)) {
            $this->active_tab = self::TINDAKAN;
            $this->editing_data_action = true;
            if ($this->action_confirm == 'revisi-permohonan' && !$this->keterangan)
                throw ValidationException::withMessages([
                    'keterangan' => 'Keterangan revisi permohonan harus diisi!'
                ]);
        }
    }

    public function render()
    {
        $this->setDataPreview();
        $this->canAccess();
        if (!$this->edit_entri_data)
            $this->setEntriData();
        return view('livewire.dashboard.preview-izin.preview-data-izin');
    }

    public function saveAction()
    {
        $this->active_tab = self::TINDAKAN;
        $this->validate([
            'tanggal_peninjauan' => ['required'],
            'lokasi_izin' => ['required', 'max:255'],
        ]);
        $action_confirm_data = [
            'permohonan-selesai',
            'revisi-permohonan',
        ];
        if (!in_array($this->action_confirm, $action_confirm_data))
            throw ValidationException::withMessages([
                'action_confirm' => 'kesalahan'
            ]);
        $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
        $saved = $this->getPerizinanService()->saveRegistrationAction($this->preview_izin_id, $this->tanggal_peninjauan, $this->lokasi_izin, $this->keterangan, $this->action_confirm, $pegawai_id);
        if ($saved) {
            $this->editing_data_action = false;
            $this->action_confirm = null;
            $this->keterangan = null;
        }
    }

    public function back()
    {
        $this->dispatch('show-semua-izin');
    }

    private function setDataPreview()
    {
        if ($this->preview_izin_id) {
            $this->preview_izin = $this->getPerizinanService()->find(intval($this->preview_izin_id));
            if (!$this->preview_izin) {
                $this->dispatch('preview-izin-not-found');
                return;
            }
            $this->preview_izin_dokumen = $this->getDokumenPermohonService()->getAllByPemohonAndSyaratAndIzin($this->preview_izin->pemohon_id, $this->preview_izin->jenis_perizinan_id, $this->preview_izin_id);

            $syarat_izin = $this->getJenisPerizinanService()->getPersyaratan($this->preview_izin->jenis_perizinan_id);
            $this->preview_izin_syarat_izin = array_diff(array_keys($syarat_izin), array_keys($this->preview_izin_dokumen));
            
            if (!$this->editing_data_action) {
                $this->tanggal_peninjauan = $this->preview_izin->tanggal_peninjauan ? $this->preview_izin->tanggal_peninjauan : Carbon::now()->format('Y-m-d H:i');
                $this->lokasi_izin = $this->preview_izin->lokasi_izin;
                $this->keterangan = $this->preview_izin->ket;
            }

            $this->last_izin_status = $this->preview_izin->status_izin->last();
            
            $this->jenis_perizinan_id = $this->preview_izin->jenis_perizinan_id;
        }
    }

    private function getAlamat($pemohon_id)
    {
        $pemohon = $this->getPemohonService()->find($pemohon_id);
        return "{$pemohon->detail_alamat}, " . $this->getRegionValue('kelurahan', $pemohon->kelurahan) . ', ' . $this->getRegionValue('kecamatan', $pemohon->kecamatan) . ', ' . $this->getRegionValue('kabupaten', $pemohon->kabupaten) . ', ' . $this->getRegionValue('provinsi', $pemohon->provinsi);
    }

    private function canProcess(): bool
    {
        $allowedStatuses = [
            [StatusIzinEnum::DIDAFTARKAN, StatusIzinProgressEnum::FINISHED],
            [StatusIzinEnum::REVISI_PERSYARATAN, StatusIzinProgressEnum::FINISHED],
            [StatusIzinEnum::DITINJAU, StatusIzinProgressEnum::UNPROCESSED],
            [StatusIzinEnum::DITINJAU, StatusIzinProgressEnum::PROCESSING]
        ];

        return in_array([$this->last_izin_status->nama, $this->last_izin_status->progres], $allowedStatuses);    }

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
        return str_capitalize($value, true);
    }

    public function showImgModal($title, $path, $tipe, $syarat_id = null)
    {
        $this->modalImgTitle = $title;
        if ($tipe == 'img') {
            $this->modalImgSource = $path;
            $this->modalPdfSource = null;
        } else if ($tipe == 'pdf') {
            $this->modalPdfSource = $path;
            $this->modalImgSource = null;
            $path = route('render-file', ['file_name' => $path]);
        }
        $this->dispatch('set-preview-img-modal', [
            'path' => $path,
            'type' => $tipe,
            'pemohon_id' => $this->preview_izin->pemohon_id,
            'syarat_id' => $syarat_id,
            'izin_id' => $this->preview_izin->id
        ]);
    }

    #[On('refresh-component')]
    public function refreshComponentApp()
    {
        $this->setDataPreview();
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }

    private function getPemohonService(): PemohonService
    {
        return app(PemohonService::class);
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }

    private function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }

    private function getDokumenPermohonService(): DokumenPemohonService
    {
        return app(DokumenPemohonService::class);
    }
    
}