<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use App\Services\APIClient\RegionAPIService;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class PendaftaranIndex extends Component
{
    #[Url()]
    public $layout, $tab, $preview_izin_id;
    public $menu;
    public $preview_data_izin = false;

    private const DATA_PEMOHON = 'Data Pemohon';
    private const DATA_IZIN_BARU = 'Permohonan Izin Baru';
    private const DATA_PERPANJANGAN_IZIN = 'Perpanjangan Izin';
    private const DATA_PERUBAHAN_IZIN = 'Perubahan Izin';

    public $semua_pemohon = [],
        $semua_izin = [],
        $semua_perpanjangan = [],
        $semua_perubahan = [];
    public $search = '',
        $start_date,
        $end_date,
        $filter_status;

    public function mount()
    {
        $tabs = [
            self::DATA_PEMOHON,
            self::DATA_IZIN_BARU,
            self::DATA_PERPANJANGAN_IZIN,
            self::DATA_PERUBAHAN_IZIN
        ];

        $defaultMenu = self::DATA_PEMOHON;
        if ($this->tab !== null) {
            $isTabValid = in_array($this->tab, $tabs);
            $selectedMenu = $isTabValid ? $this->tab : $defaultMenu;
        } else {
            $selectedMenu = $defaultMenu;
        }
        $this->selectMenu($selectedMenu, false);

        $this->filter_status = StatusIzinEnum::DIDAFTARKAN;
        
        if ($this->preview_izin_id) {
            $this->preview_data_izin = true;
        }

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataList();
        return view('livewire.dashboard.pendaftaran.pendaftaran-index');
    }

    public function selectMenu($select_menu, $reset_url = true)
    {
        $this->menu = $select_menu;
        $this->dispatch('menu-selected', ['menu' => $select_menu]);
        $this->search = '';
        $this->preview_data_izin = false;
        $this->preview_izin_id = $reset_url ? null : $this->preview_izin_id;
    }

    private function setDataList()
    {
        $this->semua_pemohon = $this->getPemohonService()->getAll($this->search, $this->filter_status);
        $this->semua_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::DIDAFTARKAN, StatusIzinEnum::DITINJAU, StatusIzinEnum::REVISI_PERSYARATAN, StatusIzinEnum::BATAL], [], $this->start_date, $this->end_date, 'ASC');
    }

    #[On('change-layout')]
    public function changeLayout($value)
    {
        $this->layout = $value;
    }

    #[On('show-semua-izin')]
    public function showSemuaIzin()
    {
        $this->selectMenu(self::DATA_IZIN_BARU);
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

    public function previewDataIzin($id)
    {
        $this->preview_data_izin = true;
        $this->preview_izin_id = $id;
    }

    private function getPemohonService(): PemohonService
    {
        return app(PemohonService::class);
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getRegionService(): RegionAPIService
    {
        return app(RegionAPIService::class);
    }

}