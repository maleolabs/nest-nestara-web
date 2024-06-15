<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class PendaftaranIndex extends Component
{
    #[Url()]
    public $layout;
    public $menu;

    private const DATA_PEMOHON = 'Data Pemohon';
    private const DATA_IZIN_BARU = 'Permohonan Izin Baru';
    private const DATA_PERPANJANGAN_IZIN = 'Perpanjangan Izin';
    private const DATA_PERUBAHAN_IZIN = 'Perubahan Izin';

    public function mount()
    {
        $this->selectMenu(self::DATA_PEMOHON);
    }

    public function render()
    {
        return view('livewire.dashboard.pendaftaran.pendaftaran-index');
    }

    public function selectMenu($select_menu)
    {
        $this->menu = $select_menu;
        $this->dispatch('menu-selected', ['menu' => $select_menu]);
    }

    #[On('change-layout')]
    public function changeLayout($value)
    {
        $this->layout = $value;
    }

}