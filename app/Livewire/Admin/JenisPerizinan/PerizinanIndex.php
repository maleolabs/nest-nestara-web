<?php

namespace App\Livewire\Admin\JenisPerizinan;

use App\Livewire\Forms\DetailPerizinanForm;
use App\Services\DomainServices\JenisPerizinanService;
use Livewire\Attributes\Url;
use Livewire\Component;

class PerizinanIndex extends Component
{
    #[Url()]
    public $id;

    public $preview_id,
        $preview_jenis;

    public DetailPerizinanForm $detail_form;
    public DetailPerizinanForm $old_detail_form;

    public function mount()
    {
        $this->preview_id = decrypt_id($this->id);
        $this->preview_jenis = $this->getJenisPerizinanService()->find($this->preview_id);

        $this->setUpOldDetailForm();
    }

    public function updated($field)
    {
        if (str_starts_with($field, 'detail_form')) {
            $field = explode('.', $field)[1];
            $detail_form = $this->detail_form->all();
        }
        if (in_array($field, array_keys($detail_form))) {
            $this->detail_form->validateOnly($field);
            $this->saveDetail($field, $detail_form[$field]);
        }
    }

    public function render()
    {
        $this->setUpDetailForm();
        return view('livewire.admin.jenis-perizinan.perizinan-index');
    }

    private function saveDetail($field, $value)
    {
        $saved_data = $this->getJenisPerizinanService()->saveField($this->preview_id, $field, $value);
        if ($saved_data) $this->preview_jenis = $saved_data;
    }

    private function setUpDetailForm()
    {
        $this->detail_form->kelompok_izin = $this->preview_jenis->kelompok_izin;
        $this->detail_form->durasi = explode(' ', $this->preview_jenis->durasi)[0];
        $this->detail_form->masa_berlaku = $this->preview_jenis->masa_berlaku == 'selamanya' ? 0 : explode(' ', $this->preview_jenis->masa_berlaku)[0];
    }

    private function setUpOldDetailForm()
    {
        $this->old_detail_form->kelompok_izin = $this->preview_jenis->kelompok_izin;
        $this->old_detail_form->durasi = explode(' ', $this->preview_jenis->durasi)[0];
        $this->old_detail_form->masa_berlaku = $this->preview_jenis->masa_berlaku == 'selamanya' ? 0 : explode(' ', $this->preview_jenis->masa_berlaku)[0];
    }

    private function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}