<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Validation\ValidationException;

trait PersyaratanForm
{

    public $tipe = [],
        $pemasukan_berkas = [],
        $new_syarat = [],
        $search_syarat = [],
        $search_new_syarat = '';

    protected function setUpPersyaratanForm()
    {
        if (!$this->preview_jenis) return;

        foreach ($this->preview_jenis->syarat_izin as $syarat) {
            $this->tipe[$syarat->id] = $syarat->tipe;
            $this->pemasukan_berkas[$syarat->id] = $syarat->online ? 'online' : 'offline';
        }
    }

    protected function saveTipeSyarat($syarat_id, $value)
    {
        $value = strtolower($value);
        if (!in_array($value, ['img', 'pdf']))
            throw ValidationException::withMessages([
                "tipe.{$syarat_id}" => 'Pilihan tidak sesuai'
            ]);

        $this->getSyaratIzinService()->saveField($syarat_id, 'tipe', $value);
    }

    protected function savePemasukanBerkas($syarat_id, $value)
    {
        $value = strtolower($value);
        if (!in_array($value, ['online', 'offline']))
            throw ValidationException::withMessages([
                "pemasukan_berkas.{$syarat_id}" => 'Pilihan tidak sesuai'
            ]);

        $value = $value == 'online' ?? false;
        $this->getSyaratIzinService()->saveField($syarat_id, 'online', $value);
    }

    public function deleteSyarat($syarat_id)
    {

    }

    protected function searchNewSyarat()
    {
        $this->search_syarat = $this->getSyaratIzinService()->getAllWhereNotInJenisPerizinan($this->preview_id, $this->search_new_syarat);
        if (count($this->search_syarat) == 0 && !empty($this->search_new_syarat)) {
            $new_id = str_pad(count($this->new_syarat), 2, '0', STR_PAD_LEFT);
            $this->search_syarat[$new_id] = strtoupper($this->search_new_syarat); 
        }
    }

    public function addNewSyarat($syarat_id, $nama_syarat)
    {
        $this->new_syarat[$syarat_id] = $nama_syarat;
        $this->search_new_syarat = '';
        $this->search_syarat = [];
    }

    public function unsetNewSyarat($id_new)
    {
        if (isset($this->new_syarat[$id_new]))
            unset($this->new_syarat[$id_new]);
    }

    public function saveNewSyarat()
    {
        if (count($this->new_syarat) == 0) return;

        $this->getSyaratIzinService()->assignAllSyaratToJenisByName($this->new_syarat, $this->preview_id);

        $this->search_new_syarat = '';
        $this->search_syarat = [];
        $this->new_syarat = [];
    }

    protected function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }

}