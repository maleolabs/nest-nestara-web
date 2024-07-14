<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;

trait HasilTinjauanTrait
{

    public $all_izin = [];

    protected function setHasilTinjauanData()
    {
        $this->all_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::PROCESSING], $this->start_date, $this->end_date);

        $timteknis_status = [
            BackOfficeStatusEnum::BELUM_DIPERIKSA,
            BackOfficeStatusEnum::SUDAH_DI_PERIKSA
        ];
        $this->all_izin = array_filter($this->all_izin, function ($izin) use ($timteknis_status) {
            return in_array($izin->status_izin->last()->keterangan, $timteknis_status);
        });
    }

    protected function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

}