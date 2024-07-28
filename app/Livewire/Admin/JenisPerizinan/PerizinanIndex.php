<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\DetailForm;
use App\Livewire\Admin\JenisPerizinan\Partials\PengaturanSurat;
use App\Livewire\Admin\JenisPerizinan\Partials\PersyaratanForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class PerizinanIndex extends Component
{
    use DetailForm, PersyaratanForm, PengaturanSurat;

    #[Url()]
    public $id;

    public $preview_id,
        $preview_jenis;

    public function mount()
    {
        $this->preview_id = decrypt_id($this->id);
        $this->preview_jenis = $this->getJenisPerizinanService()->find($this->preview_id);

        $this->setUpOldDetailForm();
        $this->setUpOldPengaturanForm();
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

        if (in_array($field, ['format_surat', 'nomor_jenis_surat', 'selected_template_surat']))
            $this->savePengaturanSurat($field);
    }

    public function render()
    {
        $this->setUpDetailForm();
        $this->setUpPersyaratanForm();
        $this->setUpPengaturanSuratForm();

        return view('livewire.admin.jenis-perizinan.perizinan-index');
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