<?php

namespace App\Livewire\Admin\JenisPerizinan\Partials;

use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Validation\ValidationException;

trait PersyaratanForm
{

    public $tipe = [],
        $tipe_old = [],
        $pemasukan_berkas = [],
        $pemasukan_berkas_old = [];

    protected function setUpPersyaratanForm()
    {
        if (!$this->preview_jenis) return;

        foreach ($this->preview_jenis->syarat_izin as $syarat) {
            $this->tipe[$syarat->id] = $syarat->tipe;
            $this->pemasukan_berkas[$syarat->id] = $syarat->online ? 'online' : 'offline';
        }
    }

    protected function setUpOldPersyaratanForm()
    {
        if (!$this->preview_jenis) return;

        foreach ($this->preview_jenis->syarat_izin as $syarat) {
            $this->tipe_old[$syarat->id] = $syarat->tipe;
            $this->pemasukan_berkas_old[$syarat->id] = $syarat->online ? 'online' : 'offline';
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

    protected function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }

}