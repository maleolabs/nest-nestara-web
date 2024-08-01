<?php

namespace App\Livewire\Admin\Partials;

use App\Livewire\Forms\PasswordForm;
use App\Livewire\Forms\PegawaiForm;
use App\Livewire\Forms\UserForm;
use App\Services\DomainServices\PegawaiService;
use App\Services\DomainServices\UserService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PegawaiCreate extends Component
{

    public $step;

    protected const ROLE_STEP = 'Pilih Jenis Pegawai Yang Ingin Dibuat';
    protected const PEGAWAI_STEP = 'Lengkapi Data Pegawai Ini';
    protected const USER_STEP = 'Buatkan Akun Untuk Pegawai';
    protected const PASSWORD_STEP = 'Set Password Akun Pegawai';
    protected const RESULT_STEP = 'results';

    public $success = true;
    public $result_message = '';

    public $role;
    public PegawaiForm $new_pegawai_form;
    public UserForm $new_user_form;
    public PasswordForm $new_password_form;

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
        $peran = $this->role == PENANDATANGAN ? $this->role : null;

        if (!$peran) {
            $this->new_password_form->validateOnly('password_baru');
            $this->new_password_form->validateOnly('konfirmasi_password_baru');
        }

        $this->step = self::RESULT_STEP;

        $pegawai = $this->getPegawaiService()->save($this->new_pegawai_form->all(), $peran);

        if ($pegawai) $this->result_message = 'Berhasil membuat data pegawai penandatangan';

        if (!$peran && $pegawai) {
            $user_data = $this->new_user_form->all();
            $user_data['password'] = $this->new_password_form->password_baru;
            $user = $this->getUserService()->save(null, $user_data, $this->role);

            if (!$user) {
                $this->getPegawaiService()->delete($pegawai->id);
                $this->success = false;
                $this->result_message = 'Terjadi kesalahan silahkan coba lagi nanti!';
            } else {
                $this->getPegawaiService()->saveField($pegawai->id, 'user_id', $user->id);
            }
            $this->result_message = 'Berhasil membuat data pegawai!';
        }
    }

    public function createNew()
    {
        $this->role = null;
        $this->new_pegawai_form->reset();
        $this->new_user_form->reset();
        $this->new_password_form->reset();
        $this->step = self::ROLE_STEP;
    }

    public function nextStep()
    {
        switch ($this->step) {
            case self::ROLE_STEP:
                $this->validate([
                    'role' => ['required', Rule::in([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE, PENANDATANGAN])]
                ]);
                $this->step = self::PEGAWAI_STEP;
                break;

            case self::PEGAWAI_STEP:
                $this->new_pegawai_form->validate();
                $this->step = self::USER_STEP;
                break;

            case self::USER_STEP:
                $this->new_user_form->validateOnly('username');
                if (!empty($this->new_user_form->email)) 
                    $this->new_user_form->validateOnly('email');
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

    private function getUserService(): UserService
    {
        return app(UserService::class);
    }

    private function getPegawaiService(): PegawaiService
    {
        return app(PegawaiService::class);
    }
    
}