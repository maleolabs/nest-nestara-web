<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class DetailPerizinanForm extends Form
{

    #[Validate('required')]
    public $kelompok_izin;

    #[Validate('required')]
    public $durasi;

    #[Validate('required')]
    public $masa_berlaku; 
    
}