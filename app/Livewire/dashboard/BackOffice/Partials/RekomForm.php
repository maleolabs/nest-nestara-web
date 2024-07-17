<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PerizinanService;
use Livewire\Attributes\Url;
use Livewire\Component;

class RekomForm extends Component
{

    #[Url()]
    public $preview_izin_id;

    public $preview_izin;

    public function mount()
    {
        if ($this->preview_izin_id) $this->preview_izin = $this->getPerizinanService()->find($this->preview_izin_id);

        if ($this->preview_izin) {
            $pemohon = str_capitalize($this->preview_izin->pemohon->nama_pemohon, true);
            $jp = str_capitalize($this->preview_izin->jenis_perizinan->nama, true);
            $this->dispatch('preview-selected', ['preview' => "{$pemohon} ({$jp})"]);
        }
    }


    public function render()
    {
        return view('livewire.dashboard.back-office.partials.rekom-form');
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}