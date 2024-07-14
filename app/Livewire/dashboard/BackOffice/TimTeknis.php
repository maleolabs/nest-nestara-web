<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Livewire\Dashboard\BackOffice\Partials\HasilTinjauanTrait;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\StatusIzinEnum;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class TimTeknis extends Component
{

    use HasilTinjauanTrait;

    #[Url()]
    public $tab, $preview_izin_id;

    public $all_izin = [];

    public $search = '',
        $start_date,
        $end_date;

    public $active_menu;

    private const HASIL_TINJAUAN = 'Entri Hasil Tinjauan';
    private const BERITA_ACARA = 'Pembuatan Berita Acara Pemeriksaan';
    private const REKOMENDASI = 'Rekomendasi';

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
        $this->setHasilTinjauanData();
        return view('livewire.dashboard.back-office.tim-teknis');
    }

    public function selectMenu($menu, $clear_url = true)
    {
        $this->active_menu = $menu;
        $this->preview_izin_id = $clear_url ? null : $this->preview_izin_id;
        $this->dispatch('menu-selected', ['menu' => $menu]);
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

    protected function getTanggalPermohonan(int $izin_id): string
    {
        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin_id);
        return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
    }

    private function getStatusIzinService(): StatusIzinService
    {
        return app(StatusIzinService::class);
    }
    
}