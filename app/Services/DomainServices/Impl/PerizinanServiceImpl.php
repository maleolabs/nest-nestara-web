<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanServiceImpl implements PerizinanService
{

    protected StatusIzinService $statusIzinService;

    protected EntriDataService $entriDataService;

    public function __construct(StatusIzinService $statusIzinService, EntriDataService $entriDataService)
    {
        $this->statusIzinService = $statusIzinService;
        $this->entriDataService = $entriDataService;
    }
    
    public function save(int $jenis_izin_id, int $pemohon_id): ?Izin
    {
        $izin = Izin::create([
            'jenis_perizinan_id' => $jenis_izin_id,
            'pemohon_id' => $pemohon_id
        ]);
        $this->statusIzinService->save($izin->id, StatusIzinEnum::DIDAFTARKAN);
        return $izin;
    }

    public function find(int $id): ?Izin
    {
        return Izin::find($id);
    }

    public function getAll(
        string $search = '', 
        array $filter_status = [], 
        array $filter_status_progress = [], 
        string $filter_start_date = null, 
        string $filter_end_date = null, 
        string $sort = 'DESC'
    ): array
    {
        $filter_start_date = $filter_start_date ?? Carbon::now()->subMonth()->toDateTimeString();
        $filter_end_date = Carbon::parse($filter_end_date)->addDay()->ToDateTimeString();

        $semua_izin = Izin::where(function ($query) use ($search) {
            $query->whereHas('jenis_perizinan', function($jenis_perizinan) use ($search) {
                $jenis_perizinan->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);
            })->orWhereHas('pemohon', function ($pemohon) use ($search) {
                $pemohon->whereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('nomor_id', 'LIKE', '%' . $search . '%');
            });
        })->whereHas('status_izin', function ($status_izin) use ($filter_status, $filter_status_progress) {
            if (!empty($filter_status)) {
                $status_izin->whereIn('nama', $filter_status);
                if (!empty($filter_status_progress))
                    $status_izin->whereIn('progres', $filter_status_progress);
                $status_izin->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_izin WHERE status_izin.izin_id = izin.id)'));   
            }
        })
        ->whereBetween('created_at', [$filter_start_date, $filter_end_date])
        ->orderBy('created_at', $sort)
        ->get();

        return $semua_izin->all();
    }

    public function saveRegistrationAction(int $izin_id, string $tanggal_peninjauan, string $lokasi_izin, string $keterangan = null, bool $selesai = false, int $pegawai_id = null): bool
    {
        DB::beginTransaction();
        $izin = Izin::find($izin_id);
        if (!$izin) {
            DB::rollBack();
            return false;
        }
        $tanggal_peninjauan = Carbon::parse($tanggal_peninjauan)->toDateTimeString();
        $izin->tanggal_peninjauan = $tanggal_peninjauan;
        $izin->lokasi_izin = $lokasi_izin;
        $izin->save();

        $this->statusIzinService->updateStatusIzin($izin_id, StatusIzinEnum::DITINJAU, StatusIzinProgressEnum::PROCESSING, $keterangan, $pegawai_id);
        if ($selesai) {
            $this->statusIzinService->updateStatusIzin($izin_id, StatusIzinEnum::DITINJAU, StatusIzinProgressEnum::FINISHED, $keterangan, $pegawai_id);
        }

        DB::commit();
        return true;
    }

    public function updateIzinStatusThatHaveBeenReviewed(): void
    {
        $all_izin_will_be_updated = [];
        $all_izin_id = Izin::whereHas('status_izin', function ($status_izin) {
            $status_izin
                ->where('nama', StatusIzinEnum::DITINJAU)
                ->where('progres', StatusIzinProgressEnum::FINISHED)
                ->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_izin WHERE status_izin.izin_id = izin.id)'));
        })->pluck('id');
        foreach ($all_izin_id as $id) {
            $all_izin_will_be_updated[] = [
                'izin_id' => $id,
                'nama' => StatusIzinEnum::PROSES,
                'keterangan' => BackOfficeStatusEnum::BELUM_ENTRI,
                'progres' => StatusIzinProgressEnum::UNPROCESSED,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $this->statusIzinService->saveAllStatusIzin($all_izin_will_be_updated);
    }

    public function updateAllIzinStatusWhenEntriDataHasChanged(): void
    {
        $modifiable_izin = Izin::whereHas('status_izin', function ($status_izin) {
            $status_izin
                ->where('nama', StatusIzinEnum::PROSES)
                ->where('progres', StatusIzinProgressEnum::PROCESSING)
                ->where(function ($status_ket) {
                    $status_ket->where('keterangan', BackOfficeStatusEnum::SUDAH_ENTRI)
                        ->orWhere('keterangan', BackOfficeStatusEnum::SEDANG_ENTRI);
                })
                ->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_izin WHERE status_izin.izin_id = izin.id)'));
        })->pluck('id');
        foreach ($modifiable_izin as $izin_id) {
            $this->updateIzinStatusWhenEntriDataHasChanged($izin_id);
        }
    }

    public function updateIzinStatusWhenEntriDataHasChanged(int $izin_id): void
    {
        $izin = $this->find($izin_id);
        if (!$izin)
            return;

        $last_izin_status = $izin->status_izin->last();
        if (!$this->entriDataService->isEntriDataIzinExistsByIzinAndJenis($izin_id, $izin->jenis_perizinan_id) && $last_izin_status->progres == StatusIzinProgressEnum::PROCESSING) {
            $entri_deleted_at = $this->entriDataService->getDeletedAtByJenis($izin->jenis_perizinan_id);
            $this->statusIzinService->updateStatusIzin($izin_id, StatusIzinEnum::PROSES, StatusIzinProgressEnum::PROCESSING, BackOfficeStatusEnum::PERUBAHAN_ENTRI, $last_izin_status->pegawai_id, $entri_deleted_at);
        }
    }
    
}