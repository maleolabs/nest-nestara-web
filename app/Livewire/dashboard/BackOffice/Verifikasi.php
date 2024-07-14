<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use Livewire\Component;

class Verifikasi extends Component
{

    public $preview_izin_id,
        $preview_izin;

    public function mount()
    {
        if ($this->preview_izin_id)
            $this->preview_izin = $this->getPerizinanService()->find($this->preview_izin_id);
    }

    public function render()
    {
        return view('livewire.dashboard.back-office.verifikasi');
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}