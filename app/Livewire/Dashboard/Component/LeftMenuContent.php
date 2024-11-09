<?php

namespace App\Livewire\Dashboard\Component;

use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Livewire\Component;

class LeftMenuContent extends Component
{
    public $all_izin = [];

    public function render()
    {
        $allowed_status = [
            StatusIzinEnum::PROSES,
            StatusIzinEnum::PENYERAHAN
        ];
        $allowed_progres = [
            StatusIzinProgressEnum::CANCELED,
            StatusIzinProgressEnum::PROCESSING,
            StatusIzinProgressEnum::FINISHED,
        ];
        $allowed_keterangan = [
            BackOfficeStatusEnum::DIREKOM,
            BackOfficeStatusEnum::REKOM_CATATAN,
            BackOfficeStatusEnum::TIDAK_DIIZINKAN,
            BackOfficeStatusEnum::DIIZINKAN, 
            BackOfficeStatusEnum::BELUM_DICETAK,
            BackOfficeStatusEnum::DICETAK, 
            BackOfficeStatusEnum::IZIN_AKAN_DITERBITKAN
        ];

        $this->all_izin = $this->getPerizinanService()->getAllBySprecificFilter('', $allowed_status, $allowed_progres, $allowed_keterangan, null, null, 'DESC', 5, 'updated_at');
        
        return view('livewire.dashboard.component.left-menu-content');
    }

    protected function encryptId($id): string
    {
        return $this->getEncryptionService()->encryptId($id);
    }

    private function getEncryptionService(): EncryptionService
    {
        return app(EncryptionService::class);
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}