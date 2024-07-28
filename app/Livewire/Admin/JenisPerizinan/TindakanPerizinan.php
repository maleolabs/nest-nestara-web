<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\TindakanForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Component;

class TindakanPerizinan extends Component
{

    use TindakanForm;

    public $preview_id,
        $preview_jenis,
        $can_use_template;

    public function mount()
    {
        $this->setUpTindakanForm();
    }

    public function updated($field)
    {
        
    }

    public function render()
    {
        return view('livewire.admin.jenis-perizinan.tindakan-perizinan');
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
       
}