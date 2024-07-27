<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

use App\Livewire\Forms\DetailPerizinanForm;

trait DetailForm
{

    public DetailPerizinanForm $detail_form;
    public DetailPerizinanForm $old_detail_form;

    protected function saveDetail($field, $value)
    {
        $saved_data = $this->getJenisPerizinanService()->saveField($this->preview_id, $field, $value);
        if ($saved_data) $this->preview_jenis = $saved_data;
    }

    protected function setUpDetailForm()
    {
        $this->detail_form->kelompok_izin = $this->preview_jenis->kelompok_izin;
        $this->detail_form->durasi = explode(' ', $this->preview_jenis->durasi)[0];
        $this->detail_form->masa_berlaku = $this->preview_jenis->masa_berlaku == 'selamanya' ? 0 : explode(' ', $this->preview_jenis->masa_berlaku)[0];
    }

    protected function setUpOldDetailForm()
    {
        $this->old_detail_form->kelompok_izin = $this->preview_jenis->kelompok_izin;
        $this->old_detail_form->durasi = explode(' ', $this->preview_jenis->durasi)[0];
        $this->old_detail_form->masa_berlaku = $this->preview_jenis->masa_berlaku == 'selamanya' ? 0 : explode(' ', $this->preview_jenis->masa_berlaku)[0];
    }

}