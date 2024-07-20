<?php

namespace App\Livewire\Dashboard\Penyerahan;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Livewire\Component;

class PenyerahanIndex extends Component
{
    public $all_izin = [],
        $preview_izin_id,
        $selectedPreview;

    public $search = '',
        $start_date,
        $end_date;

    public function mount()
    {
        $this->start_date = Carbon::today()->subDays(7)->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        $this->setData();
        return view('livewire.dashboard.penyerahan.penyerahan-index');
    }

    private function setData()
    {
        $filter_status = [
            StatusIzinEnum::PENERBITAN,
            StatusIzinEnum::PENYERAHAN,
        ];
        $filter_progres = [
            StatusIzinProgressEnum::UNPROCESSED,
            StatusIzinProgressEnum::PROCESSING,
            StatusIzinProgressEnum::FINISHED,
            StatusIzinProgressEnum::CANCELED,
        ];

        $this->all_izin = $this->getPerizinanService()->getAll($this->search, $filter_status, $filter_progres, $this->start_date, $this->end_date, 'DESC', null, 'updated_at');
    }

    public function previewDataIzin($izin_id)
    {
        $this->preview_izin_id = $izin_id;
        $this->selectedPreview = $this->getPerizinanService()->find($izin_id);
    }

    public function closePreview()
    {
        $this->preview_izin_id = null;
        $this->selectedPreview = null;
    }

    public function penerbitanFinished($izin_id)
    {
        $auth_user = auth()->user();
        $pegawai_id = $auth_user->pegawai ? $auth_user->pegawai->id : null;
        $this->getPerizinanService()->penerbitanFinished($izin_id, $pegawai_id);
        $this->setData();
    }

    public function allDone($izin_id)
    {
        $auth_user = auth()->user();
        $pegawai_id = $auth_user->pegawai ? $auth_user->pegawai->id : null;
        $this->getPerizinanService()->allDone($izin_id, $pegawai_id);
        $this->setData();
    }

    protected function getTanggalPermohonan($izin_id): string
    {
        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin_id);
        return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }
    
}