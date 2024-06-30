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
            'semua_izin' => auth()->user()->pemohon->izin()->paginate(10)
        ]);
    }

    public function preview(int $id)
    {
        $this->selectedPreview = $this->getPerizinanService()->find($id);
        $this->selectedPreviewSyaratIzin = $this->getSyaratIzinService()->getByJenisAndPemohon($this->selectedPreview->jenis_perizinan_id, auth()->user()->pemohon->id);
    }

    public function closePreview()
    {
        $this->selectedPreview = null;
        $this->selectedPreviewSyaratIzin = [];
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