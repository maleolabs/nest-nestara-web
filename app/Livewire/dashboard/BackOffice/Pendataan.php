<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Carbon\Carbon;
use Livewire\Component;

class Pendataan extends Component
{

    private const DEFAULT_MENU = 'Entri Data';

    public $active_menu;

    public $search = '',
        $start_date,
        $end_date;

    public $all_izin = [];

    public function mount()
    {
        $this->selectMenu(self::DEFAULT_MENU);
    }

    public function render()
    {
        return view('livewire.dashboard.back-office.pendataan');
    }

    public function selectMenu($menu)
    {
        if (isset($this->getMenu()[$menu])) {
            if ($menu == self::DEFAULT_MENU) {
                $start_date = Carbon::parse($this->start_date)->toDateString();
                $end_date = Carbon::parse($this->end_date)->toDateString();
                $this->all_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::PROSES], [], $start_date, $end_date, 'ASC');
            }
            $this->active_menu = $menu;
            $this->dispatch('menu-selected', ['menu' => $menu]);
        }
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