<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

use App\Services\DomainServices\SuratService;

trait PengaturanSurat
{

    public $format_surat,
        $full_format_surat,
        $nomor_jenis_surat,
        $template_surat = [],
        $selected_template,
        $can_use_template = false,
        $old_pengaturan_form = [];

    protected function savePengaturanSurat($field)
    {
        $this->validate([
            $field => ['required']
        ]);

        $this->getJenisPerizinanService()->savePengaturanSurat($field, $this->$field, $this->preview_id);
        $this->setUpOldPengaturanForm($field);
    }

    protected function setUpPengaturanSuratForm()
    {
        if (!$this->preview_jenis || !$this->preview_id) return;

        $this->full_format_surat = $this->getSuratService()->getFormat($this->preview_id, false);

        $this->format_surat = $this->getJenisPerizinanService()->getJenisFormatSuratById($this->preview_id);

        $this->nomor_jenis_surat = $this->getJenisPerizinanService()->getNomorSuratById($this->preview_id);

        $this->selected_template = $this->getJenisPerizinanService()->getCurrentTemplateById($this->preview_id);

        $this->can_use_template = $this->getJenisPerizinanService()->canUseTemplateById($this->preview_id);

        $this->template_surat = $this->getJenisPerizinanService()->getAllTemplateById($this->preview_id);
    }

    protected function setUpOldPengaturanForm($skip = null)
    {
        if ($skip != 'format_surat')
            $this->old_pengaturan_form['format_surat'] = $this->getJenisPerizinanService()->getJenisFormatSuratById($this->preview_id);

        if ($skip != 'nomor_jenis_surat')
            $this->old_pengaturan_form['nomor_jenis_surat'] = $this->getJenisPerizinanService()->getNomorSuratById($this->preview_id);

        if ($skip != 'selected_template')
            $this->old_pengaturan_form['selected_template'] = $this->getJenisPerizinanService()->getCurrentTemplateById($this->preview_id);
    }

    protected function getSuratService(): SuratService
    {
        return app(SuratService::class);
    }

}