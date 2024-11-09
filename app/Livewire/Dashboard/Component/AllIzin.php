<?php

namespace App\Livewire\Dashboard\Component;

use App\Services\DomainServices\Helpers\GetIzin;
use App\utils\enums\StatusIzinEnum as Status;
use App\utils\enums\StatusIzinProgressEnum as Progres;
use App\utils\enums\BackOfficeStatusEnum as OfficeStatus;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AllIzin extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date;
    public $end_date;

    private $semua_izin;

    public function mount()
    {
        $this->start_date = Carbon::today()->subWeek()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->semua_izin = $this->setData($this->getPage());
        return view('livewire.dashboard.component.all-izin', [
            'semua_izin' => $this->semua_izin
        ]);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function setData($page)
    {
        return $this->getIzin()
            ->search($this->search)
            ->filterStatus($this->getAllowedStatus())
            ->filterStatusProgress($this->getAllowedProgres())
            ->filterKeterangan($this->getAllowedKeterangan())
            ->startDate($this->start_date)
            ->endDate($this->end_date)
            ->orderBy('created_at')
            ->page($page)
            ->paginate(20);
    }

    private function getIzin(): GetIzin
    {
        return app(GetIzin::class);
    }

    private function getAllowedStatus(): array
    {
        return [
            Status::PROSES,
            Status::PENYERAHAN
        ];
    }

    private function getAllowedProgres(): array
    {
        return [
            Progres::CANCELED,
            Progres::PROCESSING,
            Progres::FINISHED,
        ];
    }

    private function getAllowedKeterangan(): array
    {
        return [
            OfficeStatus::DIREKOM,
            OfficeStatus::REKOM_CATATAN,
            OfficeStatus::TIDAK_DIIZINKAN,
            OfficeStatus::DIIZINKAN,
            OfficeStatus::BELUM_DICETAK,
            OfficeStatus::DICETAK,
            OfficeStatus::IZIN_AKAN_DITERBITKAN
        ];
    }
}