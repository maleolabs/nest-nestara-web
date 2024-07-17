<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class Rekomendasi extends Component
{

    #[Url()]
    public $preview_izin_id;

    public $all_izin = [];

    public $search = '',
        $start_date,
        $end_date;

    public $active_menu;

    public function mount()
    {
        $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
        $this->getPerizinanService()->updateStatusAllIfTinjauanLapanganAlreadyEntered($pegawai_id);

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataTable();
        return view('livewire.dashboard.back-office.rekomendasi');
    }

    public function previewDataIzin($izin_id)
    {
        $this->preview_izin_id = $izin_id;
    }

    private function setDataTable()
    {
        $this->all_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::PROCESSING], $this->start_date, $this->end_date);
    }

    protected function getTanggalPermohonan(int $izin_id): string
    {
        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin_id);
        return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
    }

    private function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}