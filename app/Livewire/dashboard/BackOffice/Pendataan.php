<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Pendataan extends Component
{

    #[Url()]
    public $tab, $preview_izin_id;

    private const DEFAULT_MENU = 'Entri Data';

    public $active_menu;

    public $search = '',
        $start_date,
        $end_date;

    public $all_izin = [],
        $count_izin = [];

    public function mount()
    {
        $this->getPerizinanService()->updateIzinStatusThatHaveBeenReviewed();
        $this->getPerizinanService()->updateAllIzinStatusWhenEntriDataHasChanged();

        $this->selectMenu($this->tab, false);

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataList();
        return view('livewire.dashboard.back-office.pendataan');
    }

    private function setDataList()
    {
        $start_date = Carbon::parse($this->start_date)->toDateString();
        $end_date = Carbon::parse($this->end_date)->toDateString();
        $this->all_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::PROSES], [], $start_date, $end_date);
        $this->count_izin[self::DEFAULT_MENU] = count($this->getFilteredDataList(self::DEFAULT_MENU));
        $this->count_izin['Penjadwalan Tim Teknis'] = count($this->getFilteredDataList('Penjadwalan Tim Teknis'));
        $this->all_izin = $this->getFilteredDataList($this->active_menu);
    }

    private function getFilteredDataList($filter): array
    {
        $filtered_data = [];
        if ($filter == self::DEFAULT_MENU) {
            $filtered_data = array_filter($this->all_izin, function ($izin) {
                $izin_status = $izin->status_izin->last();
                return in_array($izin_status->keterangan, $this->getEnterableStatuses());
            });
        } else {
            $filtered_data = array_filter($this->all_izin, function ($izin) {
                return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::ENTRI_TINJAUAN_LAPANGAN;
            });
        }
        return $filtered_data;
    }

    private function getEnterableStatuses(): array
    {
        return [
            BackOfficeStatusEnum::BELUM_ENTRI,
            BackOfficeStatusEnum::PERUBAHAN_ENTRI,
            BackOfficeStatusEnum::SEDANG_ENTRI,
            BackOfficeStatusEnum::SUDAH_ENTRI,
        ];
    }

    public function previewDataIzin($izin_id)
    {
        $this->preview_izin_id = $izin_id;
    }

    public function selectMenu($menu, $reset_url = true)
    {
        if (isset($this->getMenu()[$menu])) {
            if ($this->preview_izin_id) {
                $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
                $this->getPerizinanService()->updateStatusIfEntriDataAlrearyEntered($this->preview_izin_id, $pegawai_id);
            }
            $this->active_menu = $menu;
            $this->preview_izin_id = $reset_url ? null : $this->preview_izin_id;
            $this->dispatch('menu-selected', ['menu' => $menu]);
        } else {
            $this->selectMenu(self::DEFAULT_MENU, true);
        }
    }

    #[On('preview-izin-not-found')]
    public function onPreviewIzinNotFound()
    {
        $this->preview_izin_id = null;
    }

    private function getMenu(): array
    {
        return [
            self::DEFAULT_MENU => 'fa-solid fa-file-invoice',
            'Penjadwalan Tim Teknis' => 'fa-regular fa-calendar-check'
        ];
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}