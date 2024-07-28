<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\PengaturanSurat as PartialsPengaturanSurat;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Component;

class PengaturanSurat extends Component
{

    use PartialsPengaturanSurat;

    public $preview_id,
        $preview_jenis;

    public function mount()
    {
        $this->setUpOldPengaturanForm();
    }

    public function updated($field)
    {
        if (in_array($field, ['format_surat', 'nomor_jenis_surat', 'selected_template_surat']))
            $this->savePengaturanSurat($field);
    }

    public function render()
    {
        $this->setUpPengaturanSuratForm();
        
        return view('livewire.admin.jenis-perizinan.pengaturan-surat');
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}