<?php

namespace App\Services\DomainServices\Impl;

use App\Models\Izin;
use App\Models\RiwayatProsesIzin;
use App\Services\DomainServices\StatusIzinService;
use App\Models\StatusIzin;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum as Progres;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Support\Facades\DB;

class StatusIzinServiceImpl implements StatusIzinService {

    public function save(int $izin_id, string $nama_status): ?StatusIzin
	{
		return StatusIzin::create([
			'izin_id' => $izin_id,
			'nama' => $nama_status,
			'progres' => Progres::FINISHED
		]);
	}

	public function findByNamaAndIzin(string $nama, int $izin_id): ?StatusIzin
	{
		return StatusIzin::where('nama', $nama)->where('izin_id', $izin_id)->first();
	}

	public function findLastStatusByIzin(int $izin_id): ?StatusIzin
	{
		return StatusIzin::where('izin_id', $izin_id)->latest('created_at')->latest('id')->first();
	}

	public function getRiwayatProses(int $status_id): array
	{
		$riwayat_proses = RiwayatProsesIzin::where('status_izin_id', $status_id)->get();
		return $riwayat_proses->all();
	}

	public function getRiwayatProsesByIzin(int $izin_id): array
	{
		$riwayat_proses = RiwayatProsesIzin::where('izin_id', $izin_id)->get();
		return $riwayat_proses->all();
	}

	public function saveAllStatusIzin(array $status_izin_data): void
	{
		DB::table('status_izin')->insert($status_izin_data);
	}

	public function updateStatusIzin(int $izin_id, string $status, string $progres, ?string $ket = null, ?int $pegawai_id = null, $updated_at = null, bool $new = false, bool $keep_pegawai = false): void
	{
		if (!in_array($status, StatusIzinEnum::all(), true) || !in_array($progres, Progres::all(), true)) {
			throw new \InvalidArgumentException('Invalid status or progress');
		}

		DB::transaction(function () use ($izin_id, $status, $progres, $ket, $pegawai_id, $updated_at, $new, $keep_pegawai): void {
		// Serialize workflow changes per permit, then inspect only current status.
		Izin::whereKey($izin_id)->lockForUpdate()->first();
		$status_izin = StatusIzin::where('izin_id', $izin_id)->latest('created_at')->latest('id')->lockForUpdate()->first();
		if ($status_izin && $status_izin->nama !== $status && !$this->canTransition($status_izin->nama, $status)) {
			throw new \InvalidArgumentException("Invalid permit transition from {$status_izin->nama} to {$status}");
		}

		$target = !$new && $status_izin?->nama === $status ? $status_izin : null;
		if ($target && $target->progres === $progres && $target->keterangan === $ket && ($keep_pegawai || $target->pegawai_id === $pegawai_id)) {
			return; // Idempotent repeated UI action.
		}
		$status_izin = $target;
		if ($status_izin && !$new) {
			$status_izin->progres = $progres;
			$status_izin->keterangan = $ket;
			$status_izin->updated_at = $updated_at ?? now();

			if (!$keep_pegawai)
				$status_izin->pegawai_id = $pegawai_id;

			$status_izin->save();
		} else {
			$status_izin = StatusIzin::create([
				'izin_id' => $izin_id,
				'nama' => $status,
				'progres' => $progres,
				'keterangan' => $ket,
				'pegawai_id' => $pegawai_id
			]);
		}

		if ($status_izin->nama == StatusIzinEnum::PROSES) {
			RiwayatProsesIzin::create([
				'izin_id' => $izin_id,
				'status_izin_id' => $status_izin->id,
				'pegawai_id' => $keep_pegawai ? $status_izin->pegawai_id : $pegawai_id,
				'progres' => $status_izin->progres,
				'keterangan' => $status_izin->keterangan,
			]);
		}

		$izin = Izin::find($izin_id);
		if ($izin)
			$izin->touch();
		});
	}

	/** Workflow guard. Same-state updates remain valid for process detail changes. */
	private function canTransition(string $from, string $to): bool
	{
		return match ($from) {
			StatusIzinEnum::DIDAFTARKAN => in_array($to, [StatusIzinEnum::DITINJAU, StatusIzinEnum::REVISI_PERSYARATAN, StatusIzinEnum::BATAL], true),
			StatusIzinEnum::DITINJAU => in_array($to, [StatusIzinEnum::PROSES, StatusIzinEnum::REVISI_PERSYARATAN, StatusIzinEnum::BATAL], true),
			StatusIzinEnum::REVISI_PERSYARATAN => in_array($to, [StatusIzinEnum::DITINJAU, StatusIzinEnum::PROSES, StatusIzinEnum::BATAL], true),
			StatusIzinEnum::PROSES => in_array($to, [StatusIzinEnum::PENERBITAN, StatusIzinEnum::BATAL], true),
			StatusIzinEnum::PENERBITAN => in_array($to, [StatusIzinEnum::PENYERAHAN, StatusIzinEnum::BATAL], true),
			StatusIzinEnum::PENYERAHAN => $to === StatusIzinEnum::BATAL,
			StatusIzinEnum::BATAL => false,
			default => in_array($to, StatusIzinEnum::all(), true),
		};
	}

	public function updateStatusIzinWithDetailKeterangan(int $izin_id, string $status, string $progres, ?string $keterangan = null, ?string $detail_keterangan = null): void
	{
		if (!in_array($status, StatusIzinEnum::all(), true) || !in_array($progres, Progres::all(), true)) {
			throw new \InvalidArgumentException('Invalid status or progress');
		}

		DB::transaction(function () use ($izin_id, $status, $progres, $keterangan, $detail_keterangan): void {
			Izin::whereKey($izin_id)->lockForUpdate()->first();
			$status_izin = StatusIzin::where('izin_id', $izin_id)->latest('created_at')->latest('id')->lockForUpdate()->first();
			if (!$status_izin || $status_izin->nama !== $status) {
				throw new \InvalidArgumentException('Status is not current permit status');
			}

			$status_izin->progres = $progres;
			
			if ($keterangan)
				$status_izin->keterangan = $keterangan;

			if ($detail_keterangan)
				$status_izin->detail_keterangan = $detail_keterangan;
			
			$status_izin->updated_at = now();
			$status_izin->save();
		});
	}

    public function appendStatusIzinWithDetail(int $izin_id, string $status, string $progres, string $keterangan, string $detail_keterangan, ?int $pegawai_id = null): void
    {
        if (!in_array($status, StatusIzinEnum::all(), true) || !in_array($progres, Progres::all(), true)) {
            throw new \InvalidArgumentException('Invalid status or progress');
        }

        DB::transaction(function () use ($izin_id, $status, $progres, $keterangan, $detail_keterangan, $pegawai_id): void {
            if (!Izin::whereKey($izin_id)->lockForUpdate()->exists()) {
                throw new \InvalidArgumentException('Permit not found');
            }
            $current = StatusIzin::where('izin_id', $izin_id)->latest('created_at')->latest('id')->lockForUpdate()->first();
            if (!$current || $current->nama !== $status || $current->progres !== $progres) {
                // Repeated submit after first request already appended result.
                // Keep operation UX-safe; current recommendation is terminal for this action.
                if ($current && $current->nama === $status && $current->progres === $progres
                    && $current->keterangan === $keterangan && $current->detail_keterangan === $detail_keterangan) {
                    return;
                }
                throw new \InvalidArgumentException('Permit status is no longer eligible');
            }
            if ($current->keterangan === $keterangan && $current->detail_keterangan === $detail_keterangan) {
                return;
            }
            $new = StatusIzin::create(['izin_id' => $izin_id, 'nama' => $status, 'progres' => $progres, 'keterangan' => $keterangan, 'detail_keterangan' => $detail_keterangan, 'pegawai_id' => $pegawai_id]);
            RiwayatProsesIzin::create(['izin_id' => $izin_id, 'status_izin_id' => $new->id, 'pegawai_id' => $pegawai_id, 'progres' => $progres, 'keterangan' => $keterangan]);
        });
    }

	public function deleteAllByIzinId(int $izin_id): void
	{
		StatusIzin::where('izin_id', $izin_id)->delete();
	}

}
