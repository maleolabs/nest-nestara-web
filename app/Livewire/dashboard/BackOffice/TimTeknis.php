<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class TimTeknis extends Component
{

    #[Url()]
    public $tab, $preview_izin_id;

    public $all_izin = [],
        $count_hasil_tinjauan = 0,
        $count_berita_acara = 0,
        $count_rekomendasi = 0;

    public $search = '',
        $start_date,
        $end_date;

    public $active_menu;

    private const HASIL_TINJAUAN = 'entri-hasil-tinjauan';
    private const BERITA_ACARA = 'pembuatan-berita-acara-pemeriksaan';
    private const REKOMENDASI = 'rekomendasi';

    public function mount()
    {
        $pegawai_id = auth()->user()->pegawai ? auth()->user()->pegawai->id : null;
        $this->getPerizinanService()->updateStatusAllIfTinjauanLapanganAlreadyEntered($pegawai_id);

        if ($this->tab && in_array($this->tab, $this->getMenu())) $this->selectMenu($this->tab, false);
        else $this->selectMenu(self::HASIL_TINJAUAN, false);

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataTable();
        return view('livewire.dashboard.back-office.tim-teknis');
    }

    public function selectMenu($menu, $clear_url = true)
    {
        $this->active_menu = $menu;
        $this->preview_izin_id = $clear_url ? null : $this->preview_izin_id;
        $this->dispatch('menu-selected', ['menu' => str_capitalize(str_replace('-', ' ', $menu))]);
    }

    public function previewDataIzin($izin_id)
    {
        $this->preview_izin_id = $izin_id;
    }

    private function getMenu(): array
    {
        return [
            self::HASIL_TINJAUAN,
            self::BERITA_ACARA,
            self::REKOMENDASI,
        ];
    }

    private function setDataTable()
    {
        $this->all_izin = $this->getPerizinanService()->getAll($this->search, [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::PROCESSING], $this->start_date, $this->end_date);

        $this->count_hasil_tinjauan = count($this->getFilteredData(self::HASIL_TINJAUAN));
        $this->count_berita_acara = count($this->getFilteredData(self::BERITA_ACARA));
        $this->count_rekomendasi = count($this->getFilteredData(self::REKOMENDASI));
        $this->all_izin = $this->getFilteredData($this->active_menu);
    }

    private function getFilteredData($filter): array
    {
        $filtered_data = [];

        switch ($filter) {
            case self::HASIL_TINJAUAN:
                $hasil_tinjauan_status = [
                    BackOfficeStatusEnum::BELUM_DIPERIKSA,
                    BackOfficeStatusEnum::SUDAH_DI_PERIKSA
                ];
                $filtered_data = array_filter($this->all_izin, function ($izin) use ($hasil_tinjauan_status) {
                    return in_array($izin->status_izin->last()->keterangan, $hasil_tinjauan_status);
                });
                break;
            
            case self::BERITA_ACARA:
                $filtered_data = array_filter($this->all_izin, function ($izin) {
                    return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::SUDAH_DI_PERIKSA;
                });
                break;

            case self::REKOMENDASI:
                $filtered_data = array_filter($this->all_izin, function ($izin) {
                    return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::SUDAH_DI_PERIKSA;
                });
                break;

            default:
                $filtered_data = [];
                break;
        }

        return $filtered_data;
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