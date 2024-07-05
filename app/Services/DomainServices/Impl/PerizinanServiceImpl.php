<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanServiceImpl implements PerizinanService
{

    protected StatusIzinService $statusIzinService;

    public function __construct(StatusIzinService $statusIzinService)
    {
        $this->statusIzinService = $statusIzinService;
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

    public function getAll($search = '', $filter_status, $sort = 'DESC'): array
    {
        $semua_izin = Izin::where(function ($query) use ($search) {
            $query->whereHas('jenis_perizinan', function($jenis_perizinan) use ($search) {
                $jenis_perizinan->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);
            })->orWhereHas('pemohon', function ($pemohon) use ($search) {
                $pemohon->whereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('nomor_id', 'LIKE', '%' . $search . '%');
            });
        })->whereHas('status_izin', function ($status_izin) use ($filter_status) {
            $status_izin->where('nama', $filter_status)
                ->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_izin WHERE status_izin.izin_id = izin.id)'));   
        })->orderBy('created_at', $sort)->get();
        return $semua_izin->all();
    }

    public function saveRegistrationAction(int $izin_id, string $tanggal_peninjauan, string $lokasi_izin, bool $selesai = false): bool
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

        $this->statusIzinService->updateStatusIzin($izin_id, StatusIzinEnum::PROSES, StatusIzinProgressEnum::PROCESSING);
        DB::commit();
        return true;
    }
    
}