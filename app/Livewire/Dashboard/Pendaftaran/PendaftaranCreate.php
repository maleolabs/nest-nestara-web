<?php

namespace App\Livewire\Dashboard\Pendaftaran;

use Livewire\Attributes\Url;
use Livewire\Component;

class PendaftaranCreate extends Component
{
    
    #[Url()]
    public $type;

    public function render()
    {
        return view('livewire.dashboard.pendaftaran.pendaftaran-create');
    }

}