<?php

namespace App\Livewire\User\Izin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Livewire\Component;

class ShowIzin extends Component
{

    public $selectedPreview, 
        $selectedPreviewSyaratIzin = [];

    public function render()
    {
        return view('livewire.user.izin.show-izin', [
            'semua_izin' => auth()->user()->pemohon->izin
        ]);
    }

    public function preview(int $id)
    {
        $this->selectedPreview = $this->getPerizinanService()->find($id);
        $this->selectedPreviewSyaratIzin = $this->getSyaratIzinService()->getByJenisAndPemohon($id, auth()->user()->pemohon->id);
        dd($this->getPerizinanService()->find($id));
    }

    public function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    public function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }

    public function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }
    
}