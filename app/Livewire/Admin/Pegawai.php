<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Partials\PegawaiDetail;
use App\Livewire\Forms\PegawaiForm;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Pegawai extends Component
{

    use PegawaiDetail, WithFileUploads;

    #[Url()]
    public $tab;

    protected const FRONT_OFFICE_TAB = 'front-office';
    protected const BACK_OFFICE_TAB = 'back-office';
    protected const PENANDATANGAN_TAB = 'penandatangan';
    protected const NEW_PEGAWAI_TAB = 'buat-pegawai-baru';

    public $current_tab;

    public $users = [],
        $penandatangan = [],
        $selected_penandatangan;

    public PegawaiForm $penandatangan_form;

    public function mount()
    {
        $this->selectTab($this->tab);

        $this->dispatch('component-rendered');
    }

    public function updated($field)
    {
        if (in_array($this->current_tab, $this->pegawaiTabs())) {
            if (str_starts_with($field, 'pegawai_form')) {
                $field = str_replace('pegawai_form.', '', $field);
                $this->savePegawaiField($field, $this->pegawai_form->$field);
            }
    
            if (str_starts_with($field, 'user_form')) {
                $field = str_replace('user_form.', '', $field);
                $this->saveUserField($field, $this->user_form->$field);
            }
        }
    }

    public function render()
    {
        $this->setUsersData();
        $this->setPenandatanganData();
        
        return view('livewire.admin.pegawai');
    }

    public function selectTab($tab)
    {
        $tabs = [
            self::FRONT_OFFICE_TAB,
            self::BACK_OFFICE_TAB,
            self::PENANDATANGAN_TAB,
            self::NEW_PEGAWAI_TAB
        ];

        if (in_array($tab, $tabs)) {
            $this->current_tab = $tab;

            $this->pegawai_form->reset();
            $this->user_form->reset();
            $this->password_form->reset();

            $this->pegawai_form->clearValidation();
            $this->user_form->clearValidation();
            $this->password_form->clearValidation();
        } else {
            $this->current_tab = self::FRONT_OFFICE_TAB;
        }

        $this->to_detail = false;
        $this->user_foto = null;

        $this->cancelEditPenandatangan();
    }

    public function selectPenandatangan($id)
    {
        $penandatangan = $this->getPegawaiService()->find($id);
        $this->selected_penandatangan = $penandatangan;

        $this->penandatangan_form->gender = $penandatangan->gender;
        $this->penandatangan_form->nama = $penandatangan->nama;
        $this->penandatangan_form->jabatan = $penandatangan->jabatan;
        $this->penandatangan_form->pangkat = $penandatangan->pangkat;
        $this->penandatangan_form->nip = $penandatangan->nip;
    }

    public function savePenandatangan()
    {
        $saved_data = $this->getPegawaiService()->update($this->selected_penandatangan->id, $this->penandatangan_form->all());
        if ($saved_data) $this->cancelEditPenandatangan();
    }

    public function cancelEditPenandatangan()
    {
        $this->selected_penandatangan = null;
        $this->penandatangan_form->reset();
    }

    public function deletePenandatangan($id)
    {
        $this->getPegawaiService()->dismissPenandangan($id);
    }

    private function setUsersData()
    {
        $role = $this->current_tab == self::FRONT_OFFICE_TAB ? FRONT_OFFICE_ROLE : ($this->current_tab == self::BACK_OFFICE_TAB ? BACK_OFFICE_ROLE : '');
        $this->users = $this->getUserService()->getAllByRole($role);
    }

    private function setPenandatanganData()
    {
        $this->penandatangan = $this->getPegawaiService()->getAllPenandaTangan();
    }

    private function pegawaiTabs(): array
    {
        return [
            self::FRONT_OFFICE_TAB,
            self::BACK_OFFICE_TAB
        ];
    }
    
}