<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

use App\Livewire\Forms\PropertiDataForm;
use App\Services\DomainServices\EntriDataService;

trait PropertiDataEntriForm
{

    public $properti_data_entri = [],
        $properti_form = [],
        $properti_entri_tipe = [],
        $properti_entri_max = [],
        $properti_entri_mandatori = [],
        $new_form_active = false;

    public PropertiDataForm $properti_data_form;

    protected function setUpPropertiDataEntri()
    {
        $this->properti_data_entri = $this->getEntriDataService()->getByJenisPerizinanId($this->preview_id);
        
        foreach ($this->properti_data_entri as $data_entri) {
            $this->properti_form[$data_entri->id] = $data_entri->nama;
            $this->properti_entri_tipe[$data_entri->id] = $data_entri->tipe;
            $this->properti_entri_max[$data_entri->id] = $data_entri->max;
            $this->properti_entri_mandatori[$data_entri->id] = $data_entri->mandatori === 1 ?? false;
        }
    }

    protected function saveProperti($entri_id, $field)
    {
        $value = '';
        switch ($field) {
            case 'nama':
                if (!isset($this->properti_form[$entri_id])) return;
                $this->validate([
                    "properti_form.{$entri_id}" => ['required']
                ]);
                $value = $this->properti_form[$entri_id];
                break;
            
            case 'max':
                if (!isset($this->properti_entri_max[$entri_id]) || intval($this->properti_entri_max[$entri_id]) != $this->properti_entri_max[$entri_id]) return;
                $this->validate([
                    "properti_entri_max.{$entri_id}" => ['required']
                ]);
                $value = intval($this->properti_entri_max[$entri_id]);
                break;

            case 'tipe':
                if (!isset($this->properti_entri_tipe[$entri_id])) return;
                $this->validate([
                    "properti_entri_tipe.{$entri_id}" => ['required']
                ]);
                $value = $this->properti_entri_tipe[$entri_id];
                break;

            case 'mandatori':
                if (!isset($this->properti_entri_mandatori[$entri_id])) return;
                $this->validate([
                    "properti_entri_mandatori.{$entri_id}" => ['required']
                ]);
                $value = $this->properti_entri_mandatori[$entri_id] === true ? 1 : 0;
                break;
            
            default:
                return;
        }
        $this->getEntriDataService()->saveFieldById($entri_id, $field, $value);
    }

    public function addNewDataProperti()
    {
        $this->new_form_active = !$this->new_form_active;
        $this->properti_data_form->nama = '';
    }

    public function saveNewDataProperti()
    {
        $this->properti_data_form->validate();

        $saved = $this->getEntriDataService()->save($this->preview_id, $this->properti_data_form->all());

        if ($saved) {
            $this->addNewDataProperti();
            $this->setUpPropertiDataEntri();
        }
    }

    protected function getEntriDataService(): EntriDataService
    {
        return app(EntriDataService::class);
    }

}