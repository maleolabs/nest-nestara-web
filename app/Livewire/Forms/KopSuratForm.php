<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class KopSuratForm extends Form
{
    
    #[Rule('required')]
    public $nama_pemerintah;

    #[Rule('required')]
    public $nama_dinas;

    #[Rule('required')]
    public $alamat;

    #[Rule('required')]
    public $website;
    
}