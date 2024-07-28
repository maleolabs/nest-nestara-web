<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\DetailForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailPerizinan extends Component
{
    use DetailForm;

    public $preview_id,
        $preview_jenis;

    public function mount()
    {
        $this->setUpOldDetailForm();
    }

    public function updated($field)
    {
        $detail_form = [];
        if (str_starts_with($field, 'detail_form')) {
            $field = explode('.', $field)[1];
            $detail_form = $this->detail_form->all();
        }
        if (count($detail_form) > 0 && in_array($field, array_keys($detail_form))) {
            $this->detail_form->validateOnly($field);
            $this->saveDetail($field, $detail_form[$field]);
        }
    }

    public function render()
    {
        $this->setUpDetailForm();

        return view('livewire.admin.jenis-perizinan.detail-perizinan');
    }

    #[On('refresh-old-data')]
    public function refreshOldData()
    {
        $this->setUpOldDetailForm();
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}