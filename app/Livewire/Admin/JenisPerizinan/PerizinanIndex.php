<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\Url;
use Livewire\Component;

class PerizinanIndex extends Component
{

    #[Url()]
    public $id, $tab;

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

        $this->current_tab = $this->tab ?? self::DETAIL_TAB;
    }

    public function render()
    {
        return view('livewire.admin.jenis-perizinan.perizinan-index');
    }

    public function selectTab($tab)
    {
        $tabs = [
            self::DETAIL_TAB,
            self::PERSYARATAN_TAB,
            self::DATA_ENTRI_TAB,
            self::PENGATURAN_SURAT_TAB,
            self::TINDAKAN_TAB
        ];
        if (in_array($tab, $tabs))
            $this->current_tab = $tab;
        else
            $this->current_tab = self::DETAIL_TAB;
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}