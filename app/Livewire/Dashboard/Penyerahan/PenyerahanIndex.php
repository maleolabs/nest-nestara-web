<?php

namespace App\Livewire\Dashboard\Penyerahan;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class PenyerahanIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    /** Paginator tidak boleh public — Livewire tidak bisa dehydrate. Dikirim via render(). */
    protected $all_izin;
    public $preview_izin_id,
        $selectedPreview;

    public $search = '',
        $start_date,
        $end_date;

    protected array $tanggalPermohonanMap = [];

    public function mount()
    {
        $this->start_date = Carbon::today()->subDays(7)->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        $this->setData();
        $this->buildTanggalPermohonanMap();
        return view('livewire.dashboard.penyerahan.penyerahan-index', [
            'all_izin' => $this->all_izin,
        ]);
    }

    /** reset ke halaman 1 saat filter berubah */
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }

    protected function buildTanggalPermohonanMap(): void
    {
        $ids = collect($this->all_izin)->pluck('id')->all();
        $this->tanggalPermohonanMap = empty($ids)
            ? []
            : \Illuminate\Support\Facades\DB::table('status_izin')
                ->whereIn('izin_id', $ids)
                ->where('nama', StatusIzinEnum::DIDAFTARKAN)
                ->pluck('created_at', 'izin_id')
                ->all();
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

        $this->all_izin = $this->getPerizinanService()->get(
            $this->search,
            $filter_status,
            $filter_progres,
            [],
            $this->start_date,
            $this->end_date,
            'DESC',
            20,
            'updated_at',
            paginate: true,
            page: $this->getPage()
        );
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

    protected function getTanggalPermohonan($izin): string
    {
        // 1) dari map ringan (render list)
        $raw = $this->tanggalPermohonanMap[$izin->id ?? $izin] ?? null;
        if ($raw) {
            return Carbon::parse($raw)->translatedFormat('l, d F Y');
        }

        // 2) fallback relasi eager / query tunggal
        if ($izin instanceof \App\Models\Izin) {
            $permohonan = $izin->didaftarkanStatus;
            return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
        }

        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin);
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