<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\PropertiDataEntriForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Component;

class PropertiDataEntri extends Component
{

    use PropertiDataEntriForm;

    public $preview_id,
        $preview_jenis;

    public function mount()
    {

    }

    public function updated($field)
    {

    }

    public function render()
    {
        $this->setUpPropertiDataEntri();

        return view('livewire.admin.jenis-perizinan.properti-data-entri');
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}