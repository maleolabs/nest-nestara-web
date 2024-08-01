<?php

namespace App\Livewire\Admin\Partials;

use App\Livewire\Forms\PasswordForm;
use App\Livewire\Forms\PegawaiForm;
use App\Livewire\Forms\UserForm;
use Livewire\Component;

class PegawaiCreate extends Component
{

    public $step;

    protected const ROLE_STEP = 'Pilih Jenis Pegawai Yang Ingin Dibuat';
    protected const PEGAWAI_STEP = 'Lengkapi Data Pegawai Ini';
    protected const USER_STEP = 'Buatkan Akun Untuk Pegawai';
    protected const PASSWORD_STEP = 'Set Password Akun Pegawai';

    public $role;
    public PegawaiForm $pegawai_form;
    public UserForm $user_form;
    public PasswordForm $password_form;

    public function mount()
    {
        $this->step = self::ROLE_STEP;
    }

    public function render()
    {
        return view('livewire.admin.partials.pegawai-create');
    }

    public function saveNewPegawai()
    {
        
    }

    public function nextStep()
    {
        switch ($this->step) {
            case self::ROLE_STEP:
                $this->step = self::PEGAWAI_STEP;
                break;

            case self::PEGAWAI_STEP:
                $this->step = self::USER_STEP;
                break;

            case self::USER_STEP:
                $this->step = self::PASSWORD_STEP;
                break;

            default:
                $this->step = self::PASSWORD_STEP;
                break;
        }
    }

    public function prevStep()
    {
        switch ($this->step) {
            case self::PASSWORD_STEP:
                $this->step = self::USER_STEP;
                break;

            case self::USER_STEP:
                $this->step = self::PEGAWAI_STEP;
                break;

            case self::PEGAWAI_STEP:
                $this->step = self::ROLE_STEP;
                break;

            default:
                $this->step = self::ROLE_STEP;
                break;
        }
    }
    
}