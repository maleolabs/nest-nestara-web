<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Admin\JenisPerizinan\Partials\DetailForm;
use App\Livewire\Admin\JenisPerizinan\Partials\PersyaratanForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\Url;
use Livewire\Component;

class PerizinanIndex extends Component
{
    use DetailForm, PersyaratanForm;

    #[Url()]
    public $id;

    public $preview_id,
        $preview_jenis;

    public function mount()
    {
        $this->preview_id = decrypt_id($this->id);
        $this->preview_jenis = $this->getJenisPerizinanService()->find($this->preview_id);

        $this->setUpOldDetailForm();
        $this->setUpOldPersyaratanForm();
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
            $tipe_id = explode('.', $field)[1];
            $this->saveTipeSyarat($tipe_id, $this->tipe[$tipe_id]);
        }

        if (str_starts_with($field, 'pemasukan_berkas')) {

        }
    }

    public function render()
    {
        $this->setUpDetailForm();
        $this->setUpPersyaratanForm();

        return view('livewire.admin.jenis-perizinan.perizinan-index');
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}