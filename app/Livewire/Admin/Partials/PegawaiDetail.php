<?php

namespace App\Livewire\Admin\Partials;

use App\Livewire\Forms\PegawaiForm;
use App\Services\DomainServices\PegawaiService;

trait PegawaiDetail
{

    public PegawaiForm $pegawai_form;

    public $to_detail = false,
        $user_detail,
        $pegawai_id;

    public function userDetail($user_id = null) { 
        $this->to_detail = true; 

        $this->user_detail = $this->getUserService()->find($user_id);

        $this->setDataPegawai();
    }

    public function showAll() { $this->to_detail = false; }

    protected function setDataPegawai()
    {
        if (!$this->user_detail || !$this->user_detail->pegawai) return;

        $pegawai = $this->user_detail->pegawai;

        $this->pegawai_id = $pegawai->id;

        $this->pegawai_form->nama = $pegawai->nama;
        $this->pegawai_form->jabatan = $pegawai->jabatan;
        $this->pegawai_form->pangkat = $pegawai->pangkat;
        $this->pegawai_form->nip = $pegawai->nip;
    }

    protected function savePegawaiField($field, $value)
    {
        $this->pegawai_form->validateOnly($field);
        $this->getPegawaiService()->saveField($this->pegawai_id, $field, $value);
    }

    protected function getPegawaiService(): PegawaiService
    {
        return app(PegawaiService::class);
    }

}