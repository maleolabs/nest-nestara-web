<?php

namespace App\Livewire\Dashboard\BackOffice;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;

class Penetapan extends Component
{

    #[Url()]
    public $tab;

    #[Url()]
    public $preview_izin_id;

    private const PENETAPAN = 'Penetapan Izin';
    private const PEMBUATAN = 'Pembuatan Izin';
    private const LAYANAN_DITOLAK = 'Layanan Ditolak';

    public $preview_action,
        $table_content;

    public $active_menu;

    public $selectedPreview,
        $previewRiwayatProses = [],
        $all_izin = [],
        $count_tabs = [],
        $tindakan;

    public $search = '',
        $start_date,
        $end_date;

    public function mount()
    {
        $this->preview_action = 'id="modalRightShowBtn"';
        $this->table_content = 'Nomor Surat';

        if ($this->tab) $this->selectMenu($this->tab); else $this->selectMenu(self::PENETAPAN); 

        $this->start_date = Carbon::today()->subMonth()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    public function render()
    {
        $this->setDataTable();
        return view('livewire.dashboard.back-office.penetapan');
    }

    public function save()
    {
        if (!in_array($this->tindakan, [BackOfficeStatusEnum::DIIZINKAN, BackOfficeStatusEnum::LAYANAN_DITOLAK]))
            throw ValidationException::withMessages([
                'tindakan' => 'Tindakan tidak valid'
            ]);
        $auth_user = auth()->user();
        $pegawai = $auth_user->pegawai ? $auth_user->pegawai->id : null;
        $this->getPerizinanService()->penetapanIzin($this->selectedPreview->id, $pegawai, $this->tindakan);
    }

    public function selectMenu(string $menu)
    {
        $this->active_menu = $menu;
        $this->dispatch('menu-selected', ['menu' => $menu]);
    }

    public function previewDataIzin(int $izin_id)
    {
        $this->preview_izin_id = $izin_id;
        $this->selectedPreview = $this->getPerizinanService()->find($izin_id);
        $riwayat_proses_izin = $this->selectedPreview->riwayat_proses_izin->all();
        $this->previewRiwayatProses = [];
        array_walk($riwayat_proses_izin, function($proses_izin) {
            if ($proses_izin->keterangan == BackOfficeStatusEnum::SUDAH_ENTRI) {
                $this->previewRiwayatProses['Data Entri'] = [
                    'tanggal' => $proses_izin->created_at->translatedFormat('l, d F Y H:i'),
                    'pegawai' => $proses_izin->pegawai ? $proses_izin->pegawai->nama : $proses_izin->status_izin->pegawai->nama,
                    'status' => true,
                ];
            } else if ($proses_izin->keterangan == BackOfficeStatusEnum::SUDAH_ENTRI_TINJAUAN_LAPANGAN) {
                $this->previewRiwayatProses['Tinjauan Lapangan'] = [
                    'tanggal' => $proses_izin->created_at->translatedFormat('l, d F Y H:i'),
                    'pegawai' => $proses_izin->pegawai ? $proses_izin->pegawai->nama : $proses_izin->status_izin->pegawai->nama,
                    'status' => true,
                ];
            } else if (in_array($proses_izin->keterangan, [BackOfficeStatusEnum::DIREKOM, BackOfficeStatusEnum::REKOM_CATATAN])) {
                $this->previewRiwayatProses['Rekomendasi'] = [
                    'tanggal' => $proses_izin->created_at->translatedFormat('l, d F Y H:i'),
                    'pegawai' => $proses_izin->pegawai ? $proses_izin->pegawai->nama : $proses_izin->status_izin->pegawai->nama,
                    'status' => $proses_izin->keterangan == BackOfficeStatusEnum::DIREKOM ?? false,
                    'keterangan' => $proses_izin->keterangan,
                ];
            } else if (in_array($proses_izin->keterangan, [BackOfficeStatusEnum::DIIZINKAN, BackOfficeStatusEnum::LAYANAN_DITOLAK])) {
                $this->previewRiwayatProses['Tindakan'] = [
                    'tanggal' => $proses_izin->created_at->translatedFormat('l, d F Y H:i'),
                    'pegawai' => $proses_izin->pegawai ? $proses_izin->pegawai->nama : $proses_izin->status_izin->pegawai->nama,
                    'status' => $proses_izin->keterangan == BackOfficeStatusEnum::DIIZINKAN ?? false,
                    'keterangan' => $proses_izin->keterangan,
                ];
            } else if ($proses_izin->keterangan == BackOfficeStatusEnum::DICETAK) {
                $this->previewRiwayatProses['Cetak'] = [
                    'tanggal' => $proses_izin->created_at->translatedFormat('l, d F Y H:i'),
                    'pegawai' => $proses_izin->pegawai ? $proses_izin->pegawai->nama : $proses_izin->status_izin->pegawai->nama,
                    'status' => true,
                    'keterangan' => $proses_izin->keterangan,
                ];
            }
        });
        $this->tindakan = BackOfficeStatusEnum::DIIZINKAN;
    }

    public function closePreview()
    {
        $this->selectedPreview = null;
        $this->preview_izin_id = null;
    }

    protected function getTanggalPermohonan(int $izin_id): string
    {
        $permohonan = $this->getStatusIzinService()->findByNamaAndIzin(StatusIzinEnum::DIDAFTARKAN, $izin_id);
        return $permohonan ? $permohonan->created_at->translatedFormat('l, d F Y') : '-';
    }

    private function setDataTable()
    {
        $this->all_izin = $this->getPerizinanService()->getAllBySprecificFilter($this->search, [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::PROCESSING, StatusIzinProgressEnum::FINISHED], [BackOfficeStatusEnum::DIREKOM, BackOfficeStatusEnum::REKOM_CATATAN, BackOfficeStatusEnum::LAYANAN_DITOLAK, BackOfficeStatusEnum::DIIZINKAN, BackOfficeStatusEnum::DICETAK, BackOfficeStatusEnum::IZIN_AKAN_DITERBITKAN], $this->start_date, $this->end_date);

        $this->count_tabs[self::PENETAPAN] = count($this->getFilteredDataList(self::PENETAPAN));
        $this->count_tabs[self::PEMBUATAN] = count($this->getFilteredDataList(self::PEMBUATAN));
        $this->count_tabs[self::LAYANAN_DITOLAK] = count($this->getFilteredDataList(self::LAYANAN_DITOLAK));

        $this->all_izin = $this->getFilteredDataList($this->active_menu);
    }

    private function getFilteredDataList($filter): array
    {
        $penetapan_status = [
            BackOfficeStatusEnum::DIREKOM,
            BackOfficeStatusEnum::REKOM_CATATAN
        ];
        $filtered_data = [];
        switch ($filter) {
            case self::PENETAPAN:
                $filtered_data = array_filter($this->all_izin, function ($izin) use ($penetapan_status) {
                    return in_array($izin->status_izin->last()->keterangan, $penetapan_status);
                });
                break;

            case self::PEMBUATAN:
                $filtered_data = array_filter($this->all_izin, function ($izin) use ($penetapan_status) {
                    $disallowed_status = $penetapan_status;
                    $disallowed_status[] = BackOfficeStatusEnum::LAYANAN_DITOLAK;

                    $status_cetak = $this->getStatusCetak($izin);
                    $last_status = $izin->status_izin->last();
                    return ($last_status->keterangan == BackOfficeStatusEnum::DIIZINKAN || !$this->isAlreadyPrinted($status_cetak ?? $last_status)) && !in_array($last_status->keterangan, $disallowed_status);
                });
                break;

            case self::LAYANAN_DITOLAK:
                $filtered_data = array_filter($this->all_izin, function ($izin) {
                    return $izin->status_izin->last()->keterangan == BackOfficeStatusEnum::LAYANAN_DITOLAK;
                });
                break;
        }
        return $filtered_data;
    }

    protected function isAlreadyPrinted($status = null): bool
    {
        if (!$status)
            $status = $this->selectedPreview->status_izin->last();
        return in_array($status->keterangan, [BackOfficeStatusEnum::DICETAK, BackOfficeStatusEnum::IZIN_AKAN_DITERBITKAN]) && $status->updated_at->diffInHours(now()) > 8;
    }

    protected function getStatusCetak($izin): object|null
    {
        return $izin->status_izin->first(function ($status) {
            return in_array($status->keterangan, [BackOfficeStatusEnum::DICETAK, BackOfficeStatusEnum::IZIN_AKAN_DITERBITKAN]);
        });
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
