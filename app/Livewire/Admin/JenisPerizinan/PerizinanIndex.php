<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\DetailForm;
use App\Livewire\Admin\JenisPerizinan\Partials\PengaturanSurat;
use App\Livewire\Admin\JenisPerizinan\Partials\PersyaratanForm;
use App\Livewire\Admin\JenisPerizinan\Partials\PropertiDataEntriForm;
use App\Livewire\Admin\JenisPerizinan\Partials\TindakanForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class PerizinanIndex extends Component
{
    use DetailForm, PersyaratanForm, PropertiDataEntriForm, PengaturanSurat, TindakanForm;

    #[Url()]
    public $id;

    public $preview_id,
        $preview_jenis;

    public $current_tab;

    protected const DETAIL_TAB = 'detail';
    protected const PERSYARATAN_TAB = 'persyaratan';
    protected const DATA_ENTRI_TAB = 'properti-data-entri';
    protected const PENGATURAN_SURAT_TAB = 'pengaturan-surat';
    protected const TINDAKAN_TAB = 'tindakan';

    public function mount()
    {
        $this->preview_id = decrypt_id($this->id);
        $this->preview_jenis = $this->getJenisPerizinanService()->find($this->preview_id);

        $this->current_tab = self::DETAIL_TAB;

        
        
    }

    public function updated($field)
    {
        

        

        
    }

    public function render()
    {
        
        
        
        

        return view('livewire.admin.jenis-perizinan.perizinan-index');
    }

    public function selectTab($tab)
    {
        $this->current_tab = $tab;
    }

    #[On('refresh-old-data')]
    public function refreshOldData()
    {
        $this->setUpOldDetailForm();
        $this->setUpOldPengaturanForm();
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}