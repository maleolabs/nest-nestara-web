<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Partials\PegawaiDetail;
use App\Services\DomainServices\UserService;
use Livewire\Attributes\Url;
use Livewire\Component;

class Pegawai extends Component
{

    use PegawaiDetail;

    #[Url()]
    public $tab;

    protected const FRONT_OFFICE_TAB = 'front-office';
    protected const BACK_OFFICE_TAB = 'back-office';
    protected const PENANDATANGAN_TAB = 'penandatangan';

    public $current_tab;

    public $users = [];

    public function mount()
    {
        $this->selectTab($this->tab);
    }

    public function updated($field)
    {
        if (str_starts_with($field, 'pegawai_form')) {
            $field = str_replace('pegawai_form.', '', $field);
            $this->savePegawaiField($field, $this->pegawai_form->$field);
        }
    }

    public function render()
    {
        $this->setUsersData();
        return view('livewire.admin.pegawai');
    }

    public function selectTab($tab)
    {
        $tabs = [
            self::FRONT_OFFICE_TAB,
            self::BACK_OFFICE_TAB,
            self::PENANDATANGAN_TAB
        ];

        if (in_array($tab, $tabs)) {
            $this->current_tab = $tab;
        } else {
            $this->current_tab = self::FRONT_OFFICE_TAB;
        }

        $this->to_detail = false;
    }

    private function setUsersData()
    {
        $role = $this->current_tab == self::FRONT_OFFICE_TAB ? FRONT_OFFICE_ROLE : ($this->current_tab == self::BACK_OFFICE_TAB ? BACK_OFFICE_ROLE : '');
        $this->users = $this->getUserService()->getAllByRole($role);
    }

    private function getUserService(): UserService
    {
        return app(UserService::class);
    }
    
}