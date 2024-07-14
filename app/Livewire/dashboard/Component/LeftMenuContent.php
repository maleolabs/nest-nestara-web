<?php

namespace App\Livewire\Dashboard\Component;

use App\Services\DomainServices\PerizinanService;
use Livewire\Component;

class LeftMenuContent extends Component
{
    public $all_izin;

    public function render()
    {
        $this->all_izin = $this->getPerizinanService()->getAll('', [], [], null, null, 'DESC', 10);
        return view('livewire.dashboard.component.left-menu-content');
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}