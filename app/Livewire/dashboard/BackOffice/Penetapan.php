<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class Penetapan extends Component
{

    #[Url()]
    public $tab, $preview_izin_id;

    private const PENETAPAN = 'Penetapan Izin';
    private const PEMBUATAN = 'Pembuatan Izin';
    private const LAYANAN_DITOLAK = 'Layanan Ditolak';

    public $active_menu;

    public $all_izin = [],
        $count_tabs = [];

    public $search = '',
        $start_date,
        $end_date;

    public function mount()
    {
        if ($this->tab) $this->selectMenu($this->tab, false); else $this->selectMenu(self::PENETAPAN, false); 

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataTable();
        return view('livewire.dashboard.back-office.penetapan');
    }

    public function selectMenu(string $menu, $clear_url = true)
    {
        $this->active_menu = $menu;
        $this->preview_izin_id = $clear_url ? null : $this->preview_izin_id;
        $this->dispatch('menu-selected', ['menu' => $menu]);
    }

    public function previewDataIzin(int $izin_id)
    {
        $this->preview_izin_id = $izin_id;
    }

    protected function getTanggalPermohonan(int $izin_id): string
    {
        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin_id);
        return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
    }

    private function setDataTable()
    {
        $this->all_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::PROCESSING], $this->start_date, $this->end_date);

        $this->count_tabs[self::PENETAPAN] = count($this->getFilteredDataList(self::PENETAPAN));
        $this->count_tabs[self::PEMBUATAN] = count($this->getFilteredDataList(self::PEMBUATAN));
        $this->count_tabs[self::LAYANAN_DITOLAK] = count($this->getFilteredDataList(self::LAYANAN_DITOLAK));

        $this->all_izin = $this->getFilteredDataList($this->active_menu);
    }

    private function getFilteredDataList($filter): array
    {
        $penetapan_status = [
            BackOfficeStatusEnum::DIREKOM,
            BackOfficeStatusEnum::REKOM_CATATAN
        ];
        $filtered_data = [];
        switch ($filter) {
            case self::PENETAPAN:
                $filtered_data = array_filter($this->all_izin, function ($izin) use ($penetapan_status) {
                    return in_array($izin->status_izin->last()->keterangan, $penetapan_status);
                });
                break;

            case self::PEMBUATAN:
                $filtered_data = array_filter($this->all_izin, function ($izin) use ($penetapan_status) {
                    return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::DIIZINKAN;
                });
                break;

            case self::LAYANAN_DITOLAK:
                $filtered_data = array_filter($this->all_izin, function ($izin) use ($penetapan_status) {
                    return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::LAYANAN_DITOLAK;
                });
                break;
        }
        return $filtered_data;
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }
    
}