<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PegawaiForm extends Form
{

    #[Rule('required|max:6')]
    public $gender = 'male';

    #[Rule('required|max:100')]
    public $nama;

    #[Rule('required|max:100')]
    public $jabatan;

    #[Rule('max:100')]
    public $pangkat;

    #[Rule('required|max:25')]
    public $nip;

}