<?php

namespace App\Livewire\Admin\Pendaftaran;

use Livewire\Attributes\Url;
use Livewire\Component;

class PendaftaranCreate extends Component
{
    
    #[Url()]
    public $type;

    public function render()
    {
        return view('livewire.admin.pendaftaran.pendaftaran-create');
    }

}