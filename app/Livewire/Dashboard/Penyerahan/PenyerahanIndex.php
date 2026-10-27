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
    public $preview_izin_id;

    public $search = '',
        $start_date,
        $end_date;

    protected array $tanggalPermohonanMap = [];

    public function mount()
    {
        // Handover eligibility depends on permit status, not registration age.
        $this->start_date = null;
        $this->end_date = null;
    }

    public function render()
    {
        $this->setData();
        $this->buildTanggalPermohonanMap();
        // Keep Eloquent models out of Livewire state. Models with nested relations
        // cannot be dehydrated reliably between requests.
        $selectedPreview = $this->preview_izin_id
            ? $this->getPerizinanService()->find($this->preview_izin_id)
            : null;
        return view('livewire.dashboard.penyerahan.penyerahan-index', [
            'all_izin' => $this->all_izin,
            'selectedPreview' => $selectedPreview,
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
            // A permit can still be in the print workflow when handover is
            // opened; keep printed records visible instead of dropping them.
            StatusIzinEnum::PROSES,
            StatusIzinEnum::PENERBITAN,
            StatusIzinEnum::PENYERAHAN,
        ];
        $filter_progres = [
            StatusIzinProgressEnum::UNPROCESSED,
            StatusIzinProgressEnum::PROCESSING,
            StatusIzinProgressEnum::FINISHED,
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
            page: $this->getPage(),
            handoverPrintedOnly: true
        );
    }

    public function previewDataIzin($izin_id)
    {
        $this->preview_izin_id = $izin_id;
    }

    public function closePreview()
    {
        $this->preview_izin_id = null;
    }

    public function penerbitanFinished($izin_id)
    {
        if ((int) $this->preview_izin_id !== (int) $izin_id) return;
        $status = $this->getPerizinanService()->find($izin_id)?->latestStatus;
        if (!$status || $status->nama !== StatusIzinEnum::PENERBITAN || $status->progres !== StatusIzinProgressEnum::PROCESSING) {
            $this->dispatch('handover-feedback', type: 'error', message: 'Izin tidak berada pada tahap penerbitan.');
            return;
        }
        $auth_user = auth()->user();
        $pegawai_id = $auth_user->pegawai ? $auth_user->pegawai->id : null;
        $this->getPerizinanService()->penerbitanFinished($izin_id, $pegawai_id);
        $this->setData();
    }

    public function allDone($izin_id)
    {
        if ((int) $this->preview_izin_id !== (int) $izin_id) {
            $this->dispatch('handover-feedback', type: 'error', message: 'Izin tidak valid.');
            return;
        }

        $izin = $this->getPerizinanService()->find($izin_id);
        $status = $izin?->latestStatus;
        if (!$status || $status->nama !== StatusIzinEnum::PENYERAHAN || $status->progres !== StatusIzinProgressEnum::PROCESSING) {
            $this->dispatch('handover-feedback', type: 'error', message: 'Izin sudah selesai atau tidak bisa diserahkan.');
            return;
        }

        $auth_user = auth()->user();
        $pegawai_id = $auth_user->pegawai ? $auth_user->pegawai->id : null;
        $this->getPerizinanService()->allDone($izin_id, $pegawai_id);
        $this->preview_izin_id = null;
        $this->dispatch('handover-feedback', type: 'success', message: 'Izin berhasil ditandai telah diserahkan.');
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
