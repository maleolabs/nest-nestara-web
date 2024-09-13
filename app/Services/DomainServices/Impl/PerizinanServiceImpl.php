<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\StatusIzinService;
use App\Services\DomainServices\SuratService;
use App\utils\enums\BackOfficeStatusEnum as OfficeStatus;
use App\utils\enums\StatusIzinEnum as Status;
use App\utils\enums\StatusIzinProgressEnum as Progres;
use App\utils\enums\SuratGroupsEnum;
use Carbon\Carbon;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PerizinanServiceImpl implements PerizinanService
{

    protected JenisPerizinanService $jenisPerizinanService;

    protected StatusIzinService $statusIzinService;

    protected EntriDataService $entriDataService;

    protected SuratService $suratService;

    protected DokumenPemohonService $dokumenPemohonService;

    protected PengaturanService $pengaturanService;

    public function __construct(
        JenisPerizinanService $jenisPerizinanService,
        StatusIzinService $statusIzinService, 
        EntriDataService $entriDataService,
        SuratService $suratService,
        DokumenPemohonService $dokumenPemohonService,
        PengaturanService $pengaturanService)
    {
        $this->jenisPerizinanService = $jenisPerizinanService;
        $this->statusIzinService = $statusIzinService;
        $this->entriDataService = $entriDataService;
        $this->suratService = $suratService;
        $this->dokumenPemohonService = $dokumenPemohonService;
        $this->pengaturanService = $pengaturanService;
    }
    
    public function saveRegistration(int $jenis_izin_id, int $pemohon_id, array $persyaratan = []): ?Izin
    {
        /*$syarat_izin = $this->jenisPerizinanService->getPersyaratan($jenis_izin_id);
        dd(array_diff_key($syarat_izin, $persyaratan));
        if (array_diff_key($syarat_izin, $persyaratan) > 0) {
            // throw validation exception
        }*/

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

    public function get(
        string $search = '',
        array $filter_status = [],
        array $filter_status_progress = [],
        array $filter_keterangan = [],
        string $filter_start_date = null,
        string $filter_end_date = null,
        string $sort = 'DESC',
        ?int $limit = null,
        string $order_by = 'created_at',
        bool $paginate = false,
        ?int $page = null
    ): Collection|LengthAwarePaginator|CursorPaginator {
        $filter_start_date = $filter_start_date ?? Carbon::now()->subMonth()->toDateTimeString();
        $filter_end_date = Carbon::parse($filter_end_date)->addDay()->ToDateTimeString();

        $semua_izin = Izin::whereHas('status_izin', function ($query) use ($filter_status, $filter_status_progress, $filter_keterangan) {
            if (!empty($filter_status)) {
                $query->whereIn('status_izin.nama', $filter_status);

                if (!empty($filter_status_progress))
                    $query->whereIn('progres', $filter_status_progress);

                if (!empty($filter_keterangan))
                    $query->whereIn('keterangan', $filter_keterangan);
            }
        })
            ->where(function ($query) use ($search) {
                $query->whereHas('jenis_perizinan', function ($jenis_perizinan) use ($search) {
                    $jenis_perizinan->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);
                })->orWhereHas('pemohon', function ($pemohon) use ($search) {
                    $pemohon->whereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhere('nomor_id', 'LIKE', '%' . $search . '%');
                });
            })
            ->whereBetween('izin.created_at', [$filter_start_date, $filter_end_date])
            ->orderBy($order_by, $sort);

        if ($paginate) {
            $semua_izin = $semua_izin->paginate($limit, ['*'], 'page', $page);
        } else {
            if ($limit)
                $semua_izin = $semua_izin->limit($limit);
            $semua_izin = $semua_izin->get();
        }

        return $semua_izin;
    }

    public function getAll(
        string $search = '', 
        array $filter_status = [], 
        array $filter_status_progress = [], 
        string $filter_start_date = null, 
        string $filter_end_date = null, 
        string $sort = 'DESC',
        ?int $limit = null,
        string $order_by = 'created_at'
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
        ->orderBy($order_by, $sort);
        if ($limit)
            $semua_izin = $semua_izin->limit($limit);
        $semua_izin = $semua_izin->get();

        return $semua_izin->all();
    }

    public function getAllBySprecificFilter(
        string $search = '',
        array $filter_status = [],
        array $filter_status_progress = [],
        array $filter_keterangan = [],
        string $filter_start_date = null,
        string $filter_end_date = null,
        string $sort = 'DESC',
        ?int $limit = null,
        string $order_by = 'created_at'
    ): array {
        return $this->get($search, $filter_status, $filter_status_progress, $filter_keterangan, $filter_start_date, $filter_end_date, $sort, $limit, $order_by)->all();
    }

    public function getCount(array $status, array $progres, array $keterangan): int
    {
        return Izin::whereHas('status_izin', function ($query) use ($status, $progres, $keterangan) {
            if (!empty($status)) {
                $query->whereIn('nama', $status);

                if (!empty($progres))
                    $query->whereIn('progres', $progres);

                if (!empty($keterangan))
                    $query->whereIn('keterangan', $keterangan);

                $query->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_izin WHERE status_izin.izin_id = izin.id)'));
            }
        })->count();
    }

    public function saveRegistrationAction(int $izin_id, string $lokasi_izin, string $keterangan = null, string $status_permohonan = 'permohonan-selesai', int $pegawai_id = null): bool
    {
        DB::beginTransaction();
        $izin = Izin::find($izin_id);
        if (!$izin) {
            DB::rollBack();
            return false;
        }
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

    public function saveRegistrationRevision(int $izin_id, string $detail_keterangan = null): void
    {
        DB::beginTransaction();
        $izin = Izin::find($izin_id);
        if (!$izin) {
            DB::rollBack();
            return;
        }

        $this->statusIzinService->updateStatusIzinWithDetailKeterangan($izin_id, Status::REVISI_PERSYARATAN, Progres::FINISHED, null, $detail_keterangan);

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
                    $status_ket->whereIn('keterangan', $this->getUpdatableStatusWhenEntriDataHasChanged());
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
        if (!$this->entriDataService->isEntriDataIzinExistsByIzinAndJenis($izin_id, $izin->jenis_perizinan_id) && $last_izin_status->progres == Progres::PROCESSING && in_array($last_izin_status->keterangan, $this->getUpdatableStatusWhenEntriDataHasChanged())) {
            $entri_deleted_at = $this->entriDataService->getDeletedAtByJenis($izin->jenis_perizinan_id);
            $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, OfficeStatus::PERUBAHAN_ENTRI, $last_izin_status->pegawai_id, $entri_deleted_at);
        }
    }

    private function getUpdatableStatusWhenEntriDataHasChanged(): array
    {
        return [
            OfficeStatus::SUDAH_ENTRI,
            OfficeStatus::ENTRI_TINJAUAN_LAPANGAN
        ];
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

    public function savePeninjauanLapangan(int $izin_id, int $penandatangan_id, ?string $nomor_surat = null): void
    {
        DB::transaction(function () use ($izin_id, $penandatangan_id, $nomor_surat) {
            $izin = $this->find($izin_id);
            if (!$izin)
                return;
    
            if (!$nomor_surat)
                $nomor_surat = $this->suratService->generateNomorSurat($izin->jenis_perizinan_id);
    
            $izin->no_surat = $nomor_surat;
            $izin->penandatangan_id = $penandatangan_id;
            $izin->save();
    
            $izin_status = $izin->status_izin->last();
            $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, OfficeStatus::SUDAH_ENTRI_TINJAUAN_LAPANGAN, $izin_status->pegawai_id);
    
            $nomor_surat_parts = explode('/', $nomor_surat);
            $format_surat = $this->suratService->getActualFormat();
            $format_parts = explode('/', $format_surat);
            $nomor_terakhir = 0;
            $nomor_terakhir_jp = 0;
            foreach ($format_parts as $index => $part) {
                if ($part == '{nomor}')
                    $nomor_terakhir = $nomor_surat_parts[$index];
                else if ($part == '{no-jenis-izin}')
                    $nomor_terakhir_jp = $nomor_surat_parts[$index];
            }
            $this->suratService->updateNomorSurat($nomor_terakhir, $izin->jenis_perizinan_id, $nomor_terakhir_jp);
        });
    }

    public function updateStatusAllIfTinjauanLapanganAlreadyEntered(int $pegawai_id): void
    {
        $semua_izin = Izin::whereHas('status_izin', function ($status_izin) {
            $status_izin->where('nama', Status::PROSES)
                ->where('progres', Progres::PROCESSING)
                ->where('keterangan', OfficeStatus::SUDAH_ENTRI_TINJAUAN_LAPANGAN)
                ->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) from status_izin WHERE status_izin.izin_id = izin.id)'));
        })->get();
        foreach ($semua_izin as $izin) {
            $izin_status = $izin->status_izin->last();
            if ($izin_status->nama == Status::PROSES && $izin->status_izin->last()->keterangan == OfficeStatus::SUDAH_ENTRI_TINJAUAN_LAPANGAN) {
                $this->statusIzinService->updateStatusIzin($izin->id, Status::PROSES, Progres::PROCESSING, OfficeStatus::TIDAK_DIREKOM, $pegawai_id);
            }
        }
    }

    public function rekomendasiPermohonan(int $izin_id, int $pegawai_id, string $rekomendasi, string $jenis_kegiatan): void
    {
        $izin = $this->find($izin_id);
        if (!$izin)
            return;
        $izin_status = $izin->status_izin->last();
        if ($izin_status->nama == Status::PROSES) {
            $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, $rekomendasi, $pegawai_id);
            $this->statusIzinService->updateStatusIzinWithDetailKeterangan($izin_id, Status::PROSES, Progres::PROCESSING, $rekomendasi, $jenis_kegiatan);
        }
    }

    public function penetapanIzin(int $izin_id, int $pegawai_id, string $tindakan): void
    {
        DB::transaction(function () use ($izin_id, $pegawai_id, $tindakan) {
            $izin = $this->find($izin_id);
            if (!$izin)
                return;
            if (!in_array($tindakan, [OfficeStatus::DIIZINKAN, OfficeStatus::LAYANAN_DITOLAK]))
                return;
            $izin_status = $izin->status_izin->last();
            if ($izin_status->nama == Status::PROSES) {
                $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, $tindakan, $pegawai_id);
            }
        });
    }

    public function updateCetakIzinStatus(int $izin_id, ?int $pegawai_id = null): void
    {
        $izin = $this->find($izin_id);
        if (!$izin)
            return;

        $izin_status = $izin->status_izin->last();

        if ($izin_status->keterangan == OfficeStatus::DIIZINKAN) {
            $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::PROCESSING, OfficeStatus::DICETAK, $pegawai_id);
        }

        if (in_array($izin_status->keterangan, [OfficeStatus::DICETAK, OfficeStatus::DIIZINKAN]))
            $this->updateIzinStatusWhenAlreadyPrinted($izin_id);

        $nama_pengaturan = "IZIN-{$izin_id}-COUNTER";

        $saved_sett = $this->pengaturanService->findByNamaAndGroup($nama_pengaturan, SuratGroupsEnum::DOWNLOADED_SURAT_COUNTER);
        
        $value = $saved_sett ? $saved_sett->value : 0;
        $value = $value + 1;

        $this->pengaturanService->updateOrCreate($nama_pengaturan, $value, SuratGroupsEnum::DOWNLOADED_SURAT_COUNTER, null, true);
    }

    public function updateAllIzinStatusWhenAlreadyPrinted(): void
    {
        DB::transaction(function () {
            $all_izin = Izin::select('izin.id', 'status_izin.keterangan')
                ->join('status_izin', 'izin.id', '=', 'status_izin.izin_id')
                ->whereColumn('status_izin.created_at', '=', DB::raw('(SELECT MAX(created_at) from status_izin WHERE status_izin.izin_id = izin.id)'))
                ->pluck('status_izin.keterangan', 'izin.id');
            
            foreach ($all_izin as $izin_id => $keterangan) {
                if ($keterangan == OfficeStatus::DICETAK)
                    $this->updateIzinStatusWhenAlreadyPrinted($izin_id);
            }
        });
    }

    private function updateIzinStatusWhenAlreadyPrinted($izin_id): void
    {
        $this->statusIzinService->updateStatusIzin($izin_id, Status::PROSES, Progres::FINISHED, OfficeStatus::IZIN_AKAN_DITERBITKAN, null, null, false, true);
        $this->statusIzinService->updateStatusIzin($izin_id, Status::PENERBITAN, Progres::PROCESSING, null, null);
    }

    public function penerbitanFinished(int $izin_id, int $pegawai_id = null): void
    {
        DB::transaction(function () use ($izin_id, $pegawai_id) {
            $izin = $this->find($izin_id);
            if (!$izin)
                return;
            $izin_status = $izin->status_izin->last();
            if ($izin_status->nama == Status::PENERBITAN) {
                $this->statusIzinService->updateStatusIzin($izin_id, Status::PENERBITAN, Progres::FINISHED, OfficeStatus::IZIN_TELAH_DITERBITKAN, null, null, false, true);
                $this->statusIzinService->updateStatusIzin($izin_id, Status::PENYERAHAN, Progres::PROCESSING, null, $pegawai_id);
            }
        });
    }

    public function allDone($izin_id, $pegawai_id = null): void
    {
        DB::transaction(function () use ($izin_id, $pegawai_id) {
            $izin = $this->find($izin_id);
            if (!$izin)
                return;
            $izin_status = $izin->status_izin->last();
            if ($izin_status->nama == Status::PENYERAHAN) {
                $this->statusIzinService->updateStatusIzin($izin_id, Status::PENYERAHAN, Progres::FINISHED, OfficeStatus::IZIN_TELAH_DISERAHKAN, $pegawai_id);
            }
        });
    }

    public function getIzinDetails(int $izin_id): array
    {
        $izin = $this->find($izin_id);
        if (!$izin) {
            return [];
        }

        $details = $this->processStatusIzin($izin->status_izin->all());

        $proses_key = Status::PROSES;

        if (isset($details[$proses_key])) {
            $details = $this->insertProsesDetails($details, $izin_id, $proses_key);
        }

        if (!isset($details[Status::PENERBITAN])) {
            $details[Status::PENYERAHAN] = $this->createMissingDetails();
        }

        if (!isset($details[Status::PENYERAHAN])) {
            $details[Status::PENYERAHAN] = $this->createMissingDetails();
        }

        $this->insertDetailsData($details, $izin);

        return $details;
    }

    private function processStatusIzin(array $status_izin): array
    {
        $date_format = 'l, d F Y \P\u\k\u\l H:i \W\I\T\A';
        $details = [];

        foreach ($status_izin as $status) {
            $progres = $status->progres == Progres::FINISHED;
            $details[$status->nama] = [
                'progres' => $progres,
                'keterangan' => $status->keterangan ?? ($progres ? 'selesai' : 'belum diproses'),
                'pegawai' => $status->pegawai ? $status->pegawai->nama : null,
                'tanggal' => $status->updated_at->translatedFormat($date_format)
            ];
        }

        return $details;
    }

    private function insertDetailsData(array &$details, Izin $izin): void
    {
        if (isset($details[Status::DIDAFTARKAN])) {
            
            $data_pemohon = $this->getDataPemohon($izin);
            $dokumen_persyaratan = $this->getDokumenPersyaratan($izin);

            $details[Status::DIDAFTARKAN]['data'] = [
                'data pemohon' => [
                    'tipe' => 'text',
                    'value' => $data_pemohon
                ],
                'persyaratan' => [
                    'tipe' => 'file',
                    'value' => $dokumen_persyaratan
                ]
            ];
        } 
        
        if (isset($details[Status::DITINJAU])) {
            $details[Status::DITINJAU]['data'] = [
                'detail pendaftaran' => [
                    'tipe' => 'text',
                    'value' => ['lokasi izin' => $izin->lokasi_izin]
                ]
            ];
        }

        if (isset($details['entri data'])) {
            $details['entri data']['data'] = [
                'detail pendaftaran' => [
                    'tipe' => 'text',
                    'value' => $this->getEntriDataDetail($izin)
                ]
            ];
        }

        if (isset($details['tinjauan lapangan'])) {
            $details['tinjauan lapangan']['data'] = [
                'detail pendaftaran' => [
                    'tipe' => 'text',
                    'value' => [
                        'nomor surat' => $izin->no_surat,
                        'penandatangan' => $izin->penandatangan->nama
                    ]
                ]
            ];
        }
    }

    private function getEntriDataDetail(Izin $izin): array
    {
        $entri_data = [];

        $entri_data_izin = $izin->entri_data_izin;

        foreach ($entri_data_izin as $entri) {
            $nama_entri = $entri->entri_data->nama;
            $entri_data[$nama_entri] = $entri->value;
        }

        return $entri_data;
    }

    private function getDokumenPersyaratan(Izin $izin): array
    {
        $pemohon_id = $izin->pemohon_id;
        $jenis_perizinan_id = $izin->jenis_perizinan_id;
        $izin_id = $izin->id;

        $dokumen_persyaratan_pemohon = $this->dokumenPemohonService->getAllByPemohonAndSyaratAndIzin($pemohon_id, $jenis_perizinan_id, $izin_id);

        $disallowed_field_dokumen = [
            'id', 'pemohon_id', 'izin_id', 'created_at', 'updated_at', 'deleted_at'
        ];

        foreach ($dokumen_persyaratan_pemohon as $dokumen) {
            $dokumen_ = array_diff_key($dokumen->toArray(), array_flip($disallowed_field_dokumen));
            $dokumen_['syarat_izin'] = $dokumen->syarat_izin->nama;
            unset($dokumen_['syarat_izin_id']);
            $dokumen_persyaratan[] = $dokumen_;
        }

        return $dokumen_persyaratan;
    }

    private function getDataPemohon(Izin $izin): array
    {
        $data_pemohon = $izin->pemohon->toArray();

        $disallowed_field_data_pemohon = [
            'created_at', 'updated_at', 'deleted_at', 'id', 'user_id', 'deleted_at',
            'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'detail_alamat', 'alamat_luar_negeri'
        ];

        $data_pemohon = array_diff_key($data_pemohon, array_flip($disallowed_field_data_pemohon));

        $data_pemohon['alamat'] = convert_address($izin->pemohon->getAlamat());

        return $data_pemohon;
    }

    private function createMissingDetails(): array
    {
        return [
            'progres' => false,
            'keterangan' => 'belum diproses',
            'pegawai' => null,
            'tanggal' => null
        ];
    }

    private function insertProsesDetails(array $details, int $izin_id, string $proses_key): array
    {
        $proses_index = array_search($proses_key, array_keys($details));

        $details_before_proses = array_slice($details, 0, $proses_index, true);
        $details_after_proses = array_slice($details, $proses_index + 1, null, true);

        $riwayat_proses = $this->statusIzinService->getRiwayatProsesByIzin($izin_id);
        $proses_details = $this->processProsesDetails($riwayat_proses);

        return $details_before_proses + $proses_details + $details_after_proses;
    }

    private function processProsesDetails(array $riwayat_proses): array
    {
        $date_format = 'l, d F Y \P\u\k\u\l H:i \W\I\T\A';
        $proses_details = [];

        foreach ($riwayat_proses as $proses) {
            if (in_array($proses->keterangan, [OfficeStatus::BELUM_ENTRI, OfficeStatus::SUDAH_ENTRI, OfficeStatus::SEDANG_ENTRI])) {
                $proses_details['entri data'] = $this->createProsesDetail($proses, OfficeStatus::SUDAH_ENTRI, $date_format);
            } elseif (in_array($proses->keterangan, [OfficeStatus::ENTRI_TINJAUAN_LAPANGAN, OfficeStatus::SUDAH_ENTRI_TINJAUAN_LAPANGAN])) {
                $proses_details['tinjauan lapangan'] = $this->createProsesDetail($proses, OfficeStatus::SUDAH_ENTRI_TINJAUAN_LAPANGAN, $date_format);
            } else if (in_array($proses->keterangan, [OfficeStatus::TIDAK_DIREKOM, OfficeStatus::DIREKOM, OfficeStatus::REKOM_CATATAN, OfficeStatus::BAHAS_LANJUT])) {
                $proses_details['rekomendasi izin'] = $this->createProsesDetail($proses, [OfficeStatus::DIREKOM, OfficeStatus::REKOM_CATATAN, OfficeStatus::BAHAS_LANJUT], $date_format);
            } else if (in_array($proses->keterangan, [OfficeStatus::DIIZINKAN, OfficeStatus::LAYANAN_DITOLAK])) {
                $proses_details['penetapan izin'] = $this->createProsesDetail($proses, OfficeStatus::DIIZINKAN, $date_format);
            }
        }

        return $proses_details;
    }

    private function createProsesDetail($proses, $finishedStatus, $date_format): array
    {
        $progres = is_array($finishedStatus)
            ? in_array($proses->keterangan, $finishedStatus)
            : $proses->keterangan == $finishedStatus;

        return [
            'progres' => $progres,
            'keterangan' => $proses->keterangan,
            'pegawai' => $proses->pegawai?->nama,
            'tanggal' => $proses->updated_at->translatedFormat($date_format)
        ];
    }
    
}