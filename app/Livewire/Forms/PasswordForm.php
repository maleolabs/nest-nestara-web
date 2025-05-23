<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class PasswordForm extends Form
{

    public $password_lama;

    public $password_baru;

    public $konfirmasi_password_baru;

    public function rules()
    {
        return [
            'password_lama' => ['required'],
            'password_baru' => ['required', Password::min(6)->letters()->numbers()->symbols()],
            'konfirmasi_password_baru' => ['same:password_baru']
        ];
    }
    
}