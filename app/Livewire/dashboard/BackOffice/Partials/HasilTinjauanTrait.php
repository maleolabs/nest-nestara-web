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
        $this->all_izin = array_filter($this->all_izin, function ($izin) {
            return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::TIM_TEKNIS;
        });
    }

    protected function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

}