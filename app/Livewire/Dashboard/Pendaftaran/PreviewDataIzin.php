<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Livewire\Component;

class PreviewDataIzin extends Component
{

    public $preview_izin_id,
        $preview_izin,
        $preview_izin_syarat_izin = [];

    public $modalImgTitle,
        $modalImgSource,
        $modalPdfSource;

    public function mount()
    {
        $this->setDataPreview();
    }

    public function render()
    {
        return view('livewire.dashboard.pendaftaran.preview-data-izin');
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
        if ($tipe == 'img')
            $this->modalImgSource = $path;
        else if ($tipe == 'pdf')
            $this->modalPdfSource = $path;
        $this->dispatch('set-preview-img-modal');
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