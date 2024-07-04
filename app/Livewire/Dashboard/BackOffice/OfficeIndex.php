<?php

namespace App\Livewire\Dashboard\BackOffice;

use Livewire\Attributes\Url;
use Livewire\Component;

class OfficeIndex extends Component
{

    #[Url()]
    public $tab;

    private const PENDATAAN = 'Pendataan';
    private const TIM_TEKNIS = 'Tim Teknis';
    private const PENETAPAN = 'Penetapan';

    public $menu;

    public function mount()
    {
        $tabs = [
            self::PENDATAAN,
            self::TIM_TEKNIS,
            self::PENETAPAN,
        ];
        $this->tab != null ? (in_array($this->tab, $tabs) ? $this->selectMenu($this->tab) : $this->selectMenu(self::PENDATAAN)) : $this->selectMenu(self::PENDATAAN);
    }

    public function render()
    {
        return view('livewire.dashboard.back-office.office-index');
    }

    public function selectMenu($menu)
    {
        $this->menu = $menu;
        $this->dispatch('menu-selected', ['menu' => $menu]);
    }
    
}