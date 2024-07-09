<?php

namespace App\Livewire\User\Izin;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class RevisiIzin extends Component
{

    use WithFileUploads;

    public $izin_id,
        $izin,
        $status,
        $syarat_izin = [],
        $persyaratan = [];

    public function mount()
    {
        if ($this->izin_id) {
            $this->izin = $this->getPerizinanService()->find($this->izin_id);
            $this->status = $this->izin->status_izin->last();
        }
    }
    
    public function render()
    {
        $this->setUpData();
        return view('livewire.user.izin.revisi-izin');
    }

    private function setUpData()
    {
        if (!$this->izin)
            return;
        $this->syarat_izin = $this->getSyaratIzinService()->getByJenisAndPemohon($this->izin->jenis_perizinan_id, $this->izin->pemohon_id);
    }

    protected function canAccess(): bool
    {
        return $this->izin != null;
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }
    
}