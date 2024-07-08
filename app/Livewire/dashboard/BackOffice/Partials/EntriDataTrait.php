<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;

trait EntriDataTrait
{

    public $entri_data = [],
        $preview_izin_id,
        $last_izin_status,
        $jenis_perizinan_id,
        $entri_data_izin = [],
        $entri_data_validate = [],
        $is_entri_changed = false;

    public $edit_entri_data = false;

    public function setEntriData()
    {
        if (!is_int($this->jenis_perizinan_id))
            return;
        $this->entri_data = $this->getEntriDataService()->getByJenisPerizinanId($this->jenis_perizinan_id);
        foreach ($this->entri_data as $entri) {
            if ($entri_data_izin = $entri->entri_data_izin->where('izin_id', $this->preview_izin_id)->first())
                $this->entri_data_izin[$entri->id] = $entri_data_izin->value;
            $key_entri = "entri_data_izin.{$entri->id}";
            if ($entri->mandatori)
                $this->entri_data_validate[$key_entri][] = "max:{$entri->max}";
            $this->entri_data_validate[$key_entri][] = 'required';
        }
    }

    public function getOldEntriData()
    {
        $this->entri_data_izin = $this->getEntriDataService()->getOldEntriDataByIzin($this->preview_izin);
    }

    public function editEntriData()
    {
        $this->edit_entri_data = $this->canEditEntriData();
    }

    public function cancelEditEntriData()
    {
        $this->edit_entri_data = false;
        $this->clearValidation(array_keys($this->entri_data_validate));
    }

    public function entriData()
    {
        if ($this->last_izin_status && ($this->last_izin_status->keterangan == BackOfficeStatusEnum::BELUM_ENTRI || $this->last_izin_status->keterangan == BackOfficeStatusEnum::PERUBAHAN_ENTRI))
            $this->getStatusIzinService()->updateStatusIzin($this->preview_izin_id, StatusIzinEnum::PROSES, StatusIzinProgressEnum::PROCESSING, BackOfficeStatusEnum::SEDANG_ENTRI);
    }

    public function saveEntri()
    {
        $this->validate($this->entri_data_validate);
        $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
        $this->entri_data = $this->getEntriDataService()->saveEntriDataIzin($this->entri_data_izin, $this->preview_izin_id);
        $this->getStatusIzinService()->updateStatusIzin($this->preview_izin_id, StatusIzinEnum::PROSES, StatusIzinProgressEnum::PROCESSING, BackOfficeStatusEnum::SUDAH_ENTRI, $pegawai_id);

        if ($this->is_entri_changed) {
            $this->getEntriDataService()->deleteOldEntriData($this->jenis_perizinan_id);
            $this->is_entri_changed = false;
        }

        $this->cancelEditEntriData();
    }

    public function canEditEntriData(): bool
    {
        return $this->last_izin_status->pegawai_id == auth()->user()->pegawai->id;
    }

    public function thereIsOldEntriData(): bool
    {
        return $this->getEntriDataService()->hasTrashedEntriDataIzin($this->preview_izin_id);
    }

    protected function getEntriDataService(): EntriDataService
    {
        return app(EntriDataService::class);
    }

    protected function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }

}