<?php

namespace App\Livewire\Helper;

use App\Livewire\Forms\PasswordForm;
use App\Livewire\Forms\PegawaiForm;
use App\Livewire\Forms\UserForm;
use App\Services\DomainServices\PegawaiService;
use App\Services\DomainServices\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Profile extends Component
{

    use WithFileUploads;

    public PegawaiForm $pegawai_form;
    public UserForm $user_form, $user_form_old;
    public PasswordForm $password_form;

    public $user, $user_id, $pegawai_id;

    public $user_foto, $foto_is_default = true;

    public function mount()
    {
        $this->setUpProfileData();
    }

    public function render()
    {
        return view('livewire.helper.profile');
    }

    public function saveDataPegawai()
    {
        $this->pegawai_form->validate();

        $this->getPegawaiService()->update($this->pegawai_id, $this->pegawai_form->all());

        $this->setUpProfileData();
    }

    public function saveDataPengguna()
    {
        $change_user_form = array_diff($this->user_form->all(), $this->user_form_old->all());

        foreach ($change_user_form as $field => $value) {
            $this->getUserService()->saveField($this->user_id, $field, $value);
        }

        $this->setUpProfileData();
    }

    public function savePassword()
    {
        $this->password_form->validate();

        if (!Hash::check($this->password_form->password_lama, auth()->user()->password))
            throw ValidationException::withMessages([
                'password_form.password_lama' => 'password salah'
            ]);

        $this->getUserService()->savePassword($this->user_id, $this->password_form->password_baru);

        Auth::logout();
        return redirect()->route('login');
    }

    public function saveNewFoto()
    {
        $this->getUserService()->saveFotoFromLivewire($this->user_id, $this->user_foto->getClientOriginalPath());

        $this->user_foto = null;

        $this->setUpProfileData();
    }

    public function deleteFoto()
    {
        $this->getUserService()->deleteFoto($this->user_id);

        $this->setUpProfileData();
    }

    private function setUpProfileData()
    {
        $auth_user = $this->getUserService()->find(auth()->user()->id);

        $this->user = $auth_user;
        $this->user_id = $auth_user->id;

        $this->foto_is_default = $auth_user->foto == $auth_user->default_foto;

        if ($auth_user->hasAnyRole([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE])) {
            $this->pegawai_id = $auth_user->pegawai->id;

            $pegawai = $this->getPegawaiService()->find($this->pegawai_id);
            $this->pegawai_form->gender = $pegawai->gender;
            $this->pegawai_form->nama = $pegawai->nama;
            $this->pegawai_form->jabatan = $pegawai->jabatan;
            $this->pegawai_form->pangkat = $pegawai->pangkat;
            $this->pegawai_form->nip = $pegawai->nip;
        }

        $this->user_form->name = $auth_user->name;
        $this->user_form->username = $auth_user->username;
        $this->user_form->email = $auth_user->email;

        $this->user_form_old->name = $auth_user->name;
        $this->user_form_old->username = $auth_user->username;
        $this->user_form_old->email = $auth_user->email;
    }

    private function getPegawaiService(): PegawaiService
    {
        return app(PegawaiService::class);
    }

    private function getUserService(): UserService
    {
        return app(UserService::class);
    }
    
}