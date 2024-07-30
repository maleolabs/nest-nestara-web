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

    public function updated($field)
    {
        $kolom = '';
        if (str_starts_with($field, 'properti_form')) {$field = str_replace('properti_form.', '', $field); $kolom = 'nama';}
        else if (str_starts_with($field, 'properti_entri_tipe')) {$field = str_replace('properti_entri_tipe.', '', $field); $kolom = 'tipe';}
        else if (str_starts_with($field, 'properti_entri_max')) {$field = str_replace('properti_entri_max.', '', $field); $kolom = 'max';}
        else if (str_starts_with($field, 'properti_entri_mandatori')) {$field = str_replace('properti_entri_mandatori.', '', $field); $kolom = 'mandatori';}
        
        $this->saveProperti($field, $kolom);
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