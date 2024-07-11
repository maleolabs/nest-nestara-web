<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\BackOfficeStatusEnum as OfficeStatus;
use App\utils\enums\StatusIzinEnum as Status;
use App\utils\enums\StatusIzinProgressEnum as Progres;
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
    
    public function saveRegistration(int $jenis_izin_id, int $pemohon_id): ?Izin
    {
        $izin = Izin::create([
            'jenis_perizinan_id' => $jenis_izin_id,
            'pemohon_id' => $pemohon_id
        ]);
        $this->statusIzinService->save($izin->id, Status::DIDAFTARKAN);
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

    public function saveRegistrationAction(int $izin_id, string $tanggal_peninjauan, string $lokasi_izin, string $keterangan = null, string $status_permohonan = 'permohonan-selesai', int $pegawai_id = null): bool
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

        if ($status_permohonan == 'permohonan-selesai')
            $this->statusIzinService->updateStatusIzin($izin_id, Status::DITINJAU, Progres::FINISHED, $keterangan, $pegawai_id);
        else if ($status_permohonan == 'revisi-permohonan')
            $this->statusIzinService->updateStatusIzin($izin_id, Status::REVISI_PERSYARATAN, Progres::UNPROCESSED, $keterangan, $pegawai_id);

        DB::commit();
        return true;
    }

    public function rollbackRegistration(int $izin_id, string $keterangan, int $pegawai_id): void
    {
        DB::beginTransaction();
        $izin = Izin::find($izin_id);
        if (!$izin) {
            DB::rollBack();
            return;
        }

        $this->statusIzinService->updateStatusIzin($izin_id, Status::REVISI_PERSYARATAN, Progres::UNPROCESSED, $keterangan, $pegawai_id, null, true);

        DB::commit();
    }

    public function saveRegistrationRevision(int $izin_id, string $keterangan = null): void
    {
        DB::beginTransaction();
        $izin = Izin::find($izin_id);
        if (!$izin) {
            DB::rollBack();
            return;
        }

        $revision_status = $this->statusIzinService->findByNamaAndIzin(Status::REVISI_PERSYARATAN, $izin_id);
        $this->statusIzinService->updateStatusIzin($izin_id, Status::REVISI_PERSYARATAN, Progres::FINISHED, $keterangan, $revision_status->pegawai_id);

        DB::commit();
    }

    public function getAllStatusCancelable(): array
    {
        return [
            Status::DIDAFTARKAN,
            Status::DITINJAU,
            Status::REVISI_PERSYARATAN
        ];
    }

    public function cancelRegistration(int $izin_id, string $keterangan = null): void
    {
        DB::beginTransaction();
        $izin = Izin::find($izin_id);
        if (!$izin) {
            DB::rollBack();
            return;
        }

        $izin_last_status = $izin->status_izin->last();

        if (!in_array($izin_last_status->nama, $this->getAllStatusCancelable())) {
            DB::rollBack();
            return;
        }

        $this->statusIzinService->updateStatusIzin($izin_id, Status::BATAL, Progres::FINISHED, $keterangan);

        DB::commit();
    }

    public function delete(int $izin_id): void
    {
        DB::transaction(function () use ($izin_id) {
            $izin = Izin::find($izin_id);
            if (!$izin)
                return;
            $this->statusIzinService->deleteAllByIzinId($izin_id);
            $izin->delete();
        });
    }

    public function updateIzinStatusThatHaveBeenReviewed(): void
    {
        $all_izin_will_be_updated = [];
        $all_izin_id = Izin::whereHas('status_izin', function ($status_izin) {
            $status_izin
                ->where('nama', Status::DITINJAU)
                ->where('progres', Progres::FINISHED)
                ->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_izin WHERE status_izin.izin_id = izin.id)'));
        })->pluck('id');
        foreach ($all_izin_id as $id) {
            $all_izin_will_be_updated[] = [
                'izin_id' => $id,
                'nama' => Status::PROSES,
                'keterangan' => OfficeStatus::BELUM_ENTRI,
                'progres' => Progres::UNPROCESSED,
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
                ->where('nama', Status::PROSES)
                ->where('progres', Progres::PROCESSING)
                ->where(function ($status_ket) {
                    $status_ket->where('keterangan', OfficeStatus::SUDAH_ENTRI)
                        ->orWhere('keterangan', OfficeStatus::SEDANG_ENTRI);
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
        if (!$izin || $izin->ambil_entri_lama)
            return;

        $last_izin_status = $izin->status_izin->last();
        if (!$this->entriDataService->isEntriDataIzinExistsByIzinAndJenis($izin_id, $izin->jenis_perizinan_id) && $last_izin_status->progres == Progres::PROCESSING) {
            $entri_deleted_at = $this->entriDataService->getDeletedAtByJenis($izin->jenis_perizinan_id);
            $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, OfficeStatus::PERUBAHAN_ENTRI, $last_izin_status->pegawai_id, $entri_deleted_at);
        }
    }

    public function hasDataEntri(int $izin_id): bool
    {
        $not_allowed_keterangan = [
            OfficeStatus::BELUM_ENTRI,
            OfficeStatus::SEDANG_ENTRI
        ];
        $izin_status = $this->statusIzinService->findByNamaAndIzin(Status::PROSES, $izin_id);
        return $izin_status && !in_array($izin_status->keterangan, $not_allowed_keterangan);
    }

    public function keepOldEntri(int $izin_id): void
    {
        $izin = $this->find($izin_id);
        if (!$izin)
            return;
        $izin->ambil_entri_lama = true;
        $izin->save();
    }

    public function updateStatusIfEntriDataAlrearyEntered(int $izin_id, ?int $pegawai_id = null): void
    {
        $izin = $this->find($izin_id);
        if (!$izin)
            return;
        $izin_status = $izin->status_izin->last();
        if ($izin_status->nama == Status::PROSES && $izin->status_izin->last()->keterangan == OfficeStatus::SUDAH_ENTRI) {
            $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, OfficeStatus::ENTRI_TINJAUAN_LAPANGAN, $pegawai_id);
        }
    }
    
}