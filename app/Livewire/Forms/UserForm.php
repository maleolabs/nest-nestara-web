<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class UserForm extends Form
{
    
    #[Rule('max:255')]
    public $name;

    #[Rule('required|max:100|unique:users')]
    public $username;

    #[Rule('email|max:100|unique:users')]
    public $email;
    
}