<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use Livewire\Component;

class PendaftaranIndex extends Component
{
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
    }

}