<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

use App\Services\DomainServices\EntriDataService;

trait PropertiDataEntriForm
{

    public $properti_data_entri = [],
        $properti_form = [],
        $properti_entri_tipe = [],
        $properti_entri_max = [],
        $properti_entri_mandatori = [];

    protected function setUpPropertiDataEntri()
    {
        $this->properti_data_entri = $this->getEntriDataService()->getByJenisPerizinanId($this->preview_id);
        
        foreach ($this->properti_data_entri as $data_entri) {
            $this->properti_form[$data_entri->id] = $data_entri->nama;
            $this->properti_entri_tipe[$data_entri->id] = $data_entri->tipe;
            $this->properti_entri_max[$data_entri->id] = $data_entri->max;
            $this->properti_entri_mandatori[$data_entri->id] = $data_entri->mandatori;
        }
    }

    protected function getEntriDataService(): EntriDataService
    {
        return app(EntriDataService::class);
    }

}