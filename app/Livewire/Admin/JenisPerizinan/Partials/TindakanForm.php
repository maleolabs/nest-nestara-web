<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

trait TindakanForm
{

    public $editing_name = false,
        $deleting = false,
        $nama_jenis;

    public function setUpTindakanForm()
    {
        $this->nama_jenis = $this->preview_jenis->nama;
    }

    public function editNamaJenis() { $this->editing_name = !$this->editing_name; }

    public function saveNamaJenis()
    {
        $this->validate([
            'nama_jenis' => ['required']
        ]);

        $this->preview_jenis = $this->getJenisPerizinanService()->saveField($this->preview_id, 'nama', $this->nama_jenis);

        $this->editNamaJenis();
    }

    public function deleteJenis() { $this->deleting = !$this->deleting; }

    public function confirmDelete()
    {
        $this->getJenisPerizinanService()->delete($this->preview_id);
        return redirect()->route('admin.jenis-perizinan.index');
    }

}