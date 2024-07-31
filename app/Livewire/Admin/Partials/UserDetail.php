<?php

namespace App\Livewire\Admin\Partials;

use App\Livewire\Forms\PasswordForm;
use App\Livewire\Forms\UserForm;
use App\Services\DomainServices\UserService;

trait UserDetail
{

    public UserForm $user_form;

    public PasswordForm $password_form;

    public $user_foto,
        $user_detail,
        $user_detail_id,
        $foto_is_default = true;

    public function saveNewFoto()
    {
        $this->getUserService()->saveFotoFromLivewire($this->user_detail_id, $this->user_foto->getClientOriginalPath());

        $this->user_foto = null;

        $this->setUpUserDetail($this->user_detail_id);
    }

    public function deleteFoto()
    {
        $this->getUserService()->deleteFoto($this->user_detail_id);

        $this->setUpUserDetail($this->user_detail_id);
    }

    protected function saveUserField($field, $value)
    {
        $this->user_form->validateOnly($field);
        $this->getUserService()->saveField($this->user_detail_id, $field, $value);
    }

    protected function setUpUserDetail($user_id)
    {
        $this->user_detail = $this->getUserService()->find($user_id);
        $this->user_detail_id = $user_id;

        $this->foto_is_default = $this->user_detail->foto == $this->user_detail->default_foto;

        $this->user_form->name = $this->user_detail->name;
        $this->user_form->username = $this->user_detail->username;
        $this->user_form->email = $this->user_detail->email;
    }

    public function savePassword()
    {
        $this->password_form->validateOnly('password_baru');
        $this->password_form->validateOnly('konfirmasi_password_baru');

        $this->getUserService()->savePassword($this->user_detail_id, $this->password_form->password_baru);

        $this->password_form->reset();
    }

    protected function getUserService(): UserService
    {
        return app(UserService::class);
    }

}