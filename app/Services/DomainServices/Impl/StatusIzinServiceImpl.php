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
		return StatusIzin::where('izin_id', $izin_id)->latest('created_at')->first();
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
		$status_izin = StatusIzin::where('izin_id', $izin_id)->where('nama', $status)->lockForUpdate()->first();
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
	}

	public function updateStatusIzinWithDetailKeterangan(int $izin_id, string $status, string $progres, ?string $keterangan = null, ?string $detail_keterangan = null): void
	{
		$status_izin = StatusIzin::where('izin_id', $izin_id)->where('nama', $status)->first();
		if ($status_izin) {
			$status_izin->progres = $progres;
			
			if ($keterangan)
				$status_izin->keterangan = $keterangan;

			if ($detail_keterangan)
				$status_izin->detail_keterangan = $detail_keterangan;
			
			$status_izin->updated_at = now();
			$status_izin->save();
		}
	}

	public function deleteAllByIzinId(int $izin_id): void
	{
		StatusIzin::where('izin_id', $izin_id)->delete();
	}

}
