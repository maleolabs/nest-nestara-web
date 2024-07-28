<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\PersyaratanForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Component;

class PersyaratanIzin extends Component
{

    use PersyaratanForm;

    public $preview_id,
        $preview_jenis;

    public function updated($field)
    {
        if (str_starts_with($field, 'tipe')) {
            $syarat_id = intval(explode('.', $field)[1]);
            $this->saveTipeSyarat($syarat_id, $this->tipe[$syarat_id]);
        }

        if (str_starts_with($field, 'pemasukan_berkas')) {
            $syarat_id = intval(explode('.', $field)[1]);
            $this->savePemasukanBerkas($syarat_id, $this->pemasukan_berkas[$syarat_id]);
        }

        if ($field == 'search_new_syarat') {
            if (empty($this->search_new_syarat)) {
                $this->search_syarat = [];
            } else {
                $this->searchNewSyarat();
            }
        }
    }

    public function render()
    {
        $this->setUpPersyaratanForm();
        return view('livewire.admin.jenis-perizinan.persyaratan-izin');
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}