<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\Url;
use Livewire\Component;

class PerizinanIndex extends Component
{
    #[Url()]
    public $id;

    public $preview_id,
        $preview_jenis;

    public function mount()
    {
        $this->preview_id = decrypt_id($this->id);
        $this->preview_jenis = $this->getJenisPerizinanService()->find($this->preview_id);
    }

    public function render()
    {
        return view('livewire.admin.jenis-perizinan.perizinan-index');
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}