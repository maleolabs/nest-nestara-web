<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\PerizinanService;
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
        $this->entri_data = $this->getEntriDataService()->getByJenisPerizinanIdWithOldData($this->jenis_perizinan_id, $this->preview_izin_id);
        foreach ($this->entri_data as $entri) {
            if ($entri_data_izin = $this->getEntriDataService()->findFirstEntriIzinByDataId($entri->id, $this->preview_izin_id))
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
        if ($this->last_izin_status && ($this->last_izin_status->keterangan == BackOfficeStatusEnum::BELUM_ENTRI || $this->last_izin_status->keterangan == BackOfficeStatusEnum::PERUBAHAN_ENTRI)) {
            $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
            $this->getStatusIzinService()->updateStatusIzin($this->preview_izin_id, StatusIzinEnum::PROSES, StatusIzinProgressEnum::PROCESSING, BackOfficeStatusEnum::SEDANG_ENTRI, $pegawai_id);
        }
    }

    public function keepOldEntri()
    {
        if ($this->last_izin_status && $this->last_izin_status->keterangan == BackOfficeStatusEnum::PERUBAHAN_ENTRI) {
            $this->getStatusIzinService()->updateStatusIzin($this->preview_izin_id, StatusIzinEnum::PROSES, StatusIzinProgressEnum::PROCESSING, BackOfficeStatusEnum::SUDAH_ENTRI);
            $this->getPerizinanService()->keepOldEntri($this->preview_izin_id);
        }
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
        return $this->last_izin_status->pegawai_id == auth()->user()->pegawai->id
            && $this->last_izin_status->nama == StatusIzinEnum::PROSES
            && ($this->editableEntriDataStatus());
    }

    public function editableEntriDataStatus(): bool
    {
        return in_array($this->last_izin_status->progres, [
                StatusIzinProgressEnum::UNPROCESSED,
                StatusIzinProgressEnum::PROCESSING,
                StatusIzinProgressEnum::FINISHED
            ]);
    }

    public function isFormFillAuthorized(): bool
    {
        $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
        $has_authorized = $pegawai_id == $this->last_izin_status->pegawai_id;
        return $has_authorized && ($this->last_izin_status->keterangan == BackOfficeStatusEnum::SEDANG_ENTRI || $this->edit_entri_data);
    }

    public function thereIsOldEntriData(): bool
    {
        return $this->getEntriDataService()->hasTrashedEntriDataIzin($this->preview_izin_id) && $this->last_izin_status->keterangan != BackOfficeStatusEnum::PERUBAHAN_ENTRI;
    }

    protected function canAccessSavedEntriData(): bool
    {
        return $this->getPerizinanService()->hasDataEntri($this->preview_izin_id) && !$this->edit_entri_data;
    }

    protected function getEntriDataService(): EntriDataService
    {
        return app(EntriDataService::class);
    }

    protected function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }

    protected function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

}