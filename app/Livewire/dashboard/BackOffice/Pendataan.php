<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Pendataan extends Component
{

    #[Url()]
    public $preview_izin_id;

    private const DEFAULT_MENU = 'Entri Data';

    public $active_menu;

    public $search = '',
        $start_date,
        $end_date;

    public $all_izin = [];

    public function mount()
    {
        $this->getPerizinanService()->updateIzinStatusThatHaveBeenReviewed();

        $this->selectMenu(self::DEFAULT_MENU, false);

        $this->start_date = Carbon::today()->toDateString();
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
    }

    public function previewDataIzin($izin_id)
    {
        $this->preview_izin_id = $izin_id;
    }

    public function selectMenu($menu, $reset_url = true)
    {
        if (isset($this->getMenu()[$menu])) {
            $this->active_menu = $menu;
            $this->preview_izin_id = $reset_url ? null : $this->preview_izin_id;
            $this->dispatch('menu-selected', ['menu' => $menu]);
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