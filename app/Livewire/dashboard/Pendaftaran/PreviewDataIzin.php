<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewDataIzin extends Component
{

    public $preview_izin_id,
        $preview_izin,
        $preview_izin_syarat_izin = [];

    public $modalImgTitle,
        $modalImgSource,
        $modalPdfSource;

    public $tanggal_peninjauan,
        $lokasi_izin,
        $pendaftaran_selesai;

    public $active_tab;
    private const TINDAKAN = 'tindakan';
    private const PERSYARATAN = 'persyaratan';
    
    public function mount()
    {
        $this->pendaftaran_selesai = true;
    }

    public function render()
    {
        $this->tanggal_peninjauan = Carbon::now()->format('Y-m-d H:i');
        $this->setDataPreview();
        return view('livewire.dashboard.pendaftaran.preview-data-izin');
    }

    public function saveAction()
    {
        $this->active_tab = self::TINDAKAN;
        $this->validate([
            'tanggal_peninjauan' => ['required'],
            'lokasi_izin' => ['required', 'max:255'],
        ]);
        if (!is_bool($this->pendaftaran_selesai))
            throw ValidationException::withMessages([
                'pendaftaran_selesai' => 'kesalahan'
            ]);
        $saved = $this->getPerizinanService()->saveRegistrationAction($this->preview_izin_id, $this->tanggal_peninjauan, $this->lokasi_izin, $this->pendaftaran_selesai);
    }

    public function back()
    {
        $this->dispatch('show-semua-izin');
    }

    private function setDataPreview()
    {
        if ($this->preview_izin_id) {
            $this->preview_izin = $this->getPerizinanService()->find($this->preview_izin_id);
            $this->preview_izin_syarat_izin = $this->getSyaratIzinService()->getByJenisAndPemohon($this->preview_izin->jenis_perizinan_id, $this->preview_izin->pemohon_id);
        }
    }

    private function getAlamat($pemohon_id)
    {
        $pemohon = $this->getPemohonService()->find($pemohon_id);
        return "{$pemohon->detail_alamat}, " . $this->getRegionValue('kelurahan', $pemohon->kelurahan) . ', ' . $this->getRegionValue('kecamatan', $pemohon->kecamatan) . ', ' . $this->getRegionValue('kabupaten', $pemohon->kabupaten) . ', ' . $this->getRegionValue('provinsi', $pemohon->provinsi);
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
        return str_capitalize($value, true);
    }

    public function showImgModal($title, $path, $tipe)
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
            'type' => $tipe
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
    
}