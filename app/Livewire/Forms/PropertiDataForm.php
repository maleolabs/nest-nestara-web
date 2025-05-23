<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class PropertiDataForm extends Form
{

    #[Rule('required|string')]
    public $nama;

    #[Rule('required')]
    public $tipe = 'text';

    #[Rule('required')]
    public $max = 255;

    public $mandatori = 0;
    
}