<?php

namespace Database\Seeders;

use App\utils\enums\StatusIzinEnum as Status;
use App\utils\enums\StatusIzinProgressEnum as Progres;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * DummyDataSeeder — data uji skala besar untuk optimalisasi & load testing.
 *
 * Pakai:
 *   php artisan db:seed --class=DummyDataSeeder
 *   DUMMY_PEMOHON=1000 DUMMY_IZIN=5000 php artisan db:seed --class=DummyDataSeeder
 *
 * Yang di-generate:
 *   - User (role pemohon) + profil pemohon
 *   - Izin dengan distribusi status realistis (selesai, ditinjau, revisi, proses, batal)
 *   - Riwayat status_izin berantai + riwayat_proses_izin
 *   - Penandatangan pegawai dummy (tanpa akun user)
 */
class DummyDataSeeder extends Seeder
{
    protected int $pemohonCount = 300;

    protected int $izinCount = 1500;

    protected int $salt;

    protected array $firstNames = [
        'Adnan', 'Ahmad', 'Aisyah', 'Andi', 'Anggraeni', 'Antoni', 'Ayu', 'Bambang', 'Budi', 'Citra',
        'Dewi', 'Dian', 'Dimas', 'Eka', 'Fadli', 'Fitri', 'Galih', 'Hendra', 'Hilda', 'Ibrahim',
        'Indah', 'Irfan', 'Joko', 'Kartika', 'Lestari', 'Lukman', 'Maya', 'Mohammad', 'Nadia', 'Nurul',
        'Putra', 'Putri', 'Rahmat', 'Ratna', 'Reza', 'Rina', 'Sari', 'Siti', 'Sultan', 'Syahrul',
        'Taufik', 'Tuti', 'Usman', 'Wahyu', 'Wulan', 'Yanti', 'Yusuf', 'Zainal', 'Zulkifli', 'Novita',
    ];

    protected array $lastNames = [
        'Abdullah', 'Ahmadi', 'Bakti', 'Dunggio', 'Hasan', 'Husain', 'Ilato', 'Ipak', 'Iskandar',
        'Katili', 'Kumalasari', 'Lahay', 'Limonu', 'Mahmud', 'Mokoginta', 'Musabiq', 'Ntobo', 'Pakaya',
        'Panjaitan', 'Pulubuhu', 'Rahim', 'Ramli', 'Safitri', 'Santoso', 'Sarira', 'Sauyad', 'Setiawan',
        'Sihombing', 'Sumampau', 'Tangahu', 'Umar', 'Wijaya', 'Yasin', 'Yusuf', 'Zacharias',
    ];

    protected array $streets = [
        'Jl. Merdeka', 'Jl. Nani Wartabone', 'Jl. Bali', 'Jl. Cendrawasih', 'Jl. Anggrek',
        'Jl. Melati', 'Jl. Flamboyan', 'Jl. Taman Sari', 'Jl. Diponegoro', 'Jl. Ahmad Yani',
        'Jl. Pahlawan', 'Jl. Garuda', 'Jl. Kenanga', 'Jl. Bougenville', 'Jl. Mawar',
    ];

    protected array $kecamatanCodes = [7571010, 7571020, 7571030, 7571040, 7571050];

    public function run(): void
    {
        $this->pemohonCount = max(1, (int) env('DUMMY_PEMOHON', $this->pemohonCount));
        $this->izinCount = max(0, (int) env('DUMMY_IZIN', $this->izinCount));
        $this->salt = random_int(1000, 9999);

        $start = microtime(true);
        $this->command->info("DummyDataSeeder — target pemohon: {$this->pemohonCount}, izin baru: {$this->izinCount}");

        $this->speedUp();
        try {
            [$foIds, $boIds, $penandatanganIds] = $this->ensurePegawai();
            $pemohonIds = $this->seedPemohon();
            $jenisIds = DB::table('jenis_perizinan')->whereNull('deleted_at')->pluck('id')->all();

            if (empty($jenisIds)) {
                $this->command->warn('jenis_perizinan kosong — jalankan PersyaratanIzinSeeder dulu. Izin dilewati.');

                return;
            }
            if (empty($pemohonIds)) {
                $this->command->warn('Tidak ada pemohon — izin dilewati.');

                return;
            }

            if ($this->izinCount > 0) {
                $this->seedIzin($pemohonIds, $jenisIds, $foIds, $boIds, $penandatanganIds);
            }
        } finally {
            $this->restoreSpeed();
        }

        $secs = round(microtime(true) - $start, 2);
        $this->command->info("Selesai dalam {$secs}s.");
        $this->command->table(
            ['users', 'pemohon', 'izin', 'status_izin'],
            [[
                DB::table('users')->count(),
                DB::table('pemohon')->count(),
                DB::table('izin')->count(),
                DB::table('status_izin')->count(),
            ]]
        );
    }

    /* -----------------------------------------------------------------
     |  Pemohon (+ user & role)
     * ----------------------------------------------------------------- */

    protected function seedPemohon(): array
    {
        $current = (int) DB::table('pemohon')->count();
        $need = $this->pemohonCount - $current;

        if ($need <= 0) {
            $this->command->line("Pemohon sudah {$current} — cukup, lewati.");

            return DB::table('pemohon')->pluck('id')->all();
        }

        $roleId = DB::table('roles')->where('name', PEMOHON_ROLE)->value('id');
        $password = Hash::make('password');
        $now = now();
        $this->ensureDummyAvatars();

        $this->command->info("Membuat {$need} pemohon (+user & role)…");
        $insertedUserIds = [];

        foreach (array_chunk(range(1, $need), 500) as $chunk) {
            $userRows = [];
            foreach ($chunk as $i) {
                $seq = $current + $i;
                $gender = $seq % 2 === 0 ? 'female' : 'male';
                $username = "pmh{$this->salt}{$seq}";
                $userRows[] = [
                    'name' => $this->nama(),
                    'username' => $username,
                    'email' => "{$username}@dummy.test",
                    'email_verified_at' => $now,
                    'password' => $password,
                    'foto' => "dummy-{$gender}.png",
                    'default_foto' => "dummy-{$gender}.png",
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('users')->insert($userRows);
            $maxId = (int) DB::table('users')->max('id');
            $newIds = range($maxId - count($userRows) + 1, $maxId);
            $insertedUserIds = array_merge($insertedUserIds, $newIds);

            DB::table('model_has_roles')->insert(array_map(fn ($id) => [
                'role_id' => $roleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $id,
            ], $newIds));

            DB::table('pemohon')->insert(array_map(fn ($uid) => $this->pemohonRow($uid), $newIds));
        }

        return DB::table('pemohon')->pluck('id')->all();
    }

    protected function pemohonRow(int $userId): array
    {
        [$kec, $kel] = $this->lokasi();
        $now = now();

        return [
            'user_id' => $userId,
            'sumber_identitas' => random_int(1, 10) <= 9 ? 'ktp' : 'kk',
            'nomor_id' => $this->nik(),
            'nama_pemohon' => $this->nama(),
            'no_telepon' => '08' . random_int(11, 99) . random_int(1000000, 9999999),
            'provinsi' => 75,
            'kabupaten' => 7571,
            'kecamatan' => $kec,
            'kelurahan' => $kel,
            'detail_alamat' => $this->streets[array_rand($this->streets)] . ' No. ' . random_int(1, 200),
            'alamat_luar_negeri' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    /** Pasangan [kecamatan, kelurahan] dengan kode kelurahan ASLI dari API wilayah. */
    protected function lokasi(): array
    {
        static $cache = null;

        if ($cache === null) {
            $cache = [];
            try {
                $region = app(\App\Services\APIClient\RegionAPIService::class);
                foreach ($this->kecamatanCodes as $kec) {
                    $villages = $region->getVillagesByDistrict($kec);
                    $codes = collect($villages)->pluck('id')->all();
                    $cache[$kec] = !empty($codes) ? $codes : [(int) ($kec . '1001')];
                }
            } catch (\Throwable $e) {
                // fallback aman bila API tidak tersedia
                foreach ($this->kecamatanCodes as $kec) {
                    $cache[$kec] = [(int) ($kec . '1001')];
                }
            }
        }

        $kec = $this->kecamatanCodes[array_rand($this->kecamatanCodes)];

        return [$kec, (int) $cache[$kec][array_rand($cache[$kec])]];
    }

    /* -----------------------------------------------------------------
     |  Izin + rantai status
     * ----------------------------------------------------------------- */

    protected function seedIzin(array $pemohonIds, array $jenisIds, array $foIds, array $boIds, array $penandatanganIds): void
    {
        $this->command->info("Membuat {$this->izinCount} izin + rantai status…");
        $noStart = (int) DB::table('izin')->max('no_pendaftaran') + 1;
        $statusRows = [];
        $minIzinId = PHP_INT_MAX;
        $maxIzinId = 0;

        for ($i = 0; $i < $this->izinCount; $i++) {
            $outcome = $this->pickOutcome();
            $base = Carbon::now()->subDays(random_int(1, 365))->subHours(random_int(0, 10));
            $fo = $foIds[array_rand($foIds)];
            $bo = $boIds ? $boIds[array_rand($boIds)] : $fo;
            $noSuratSeq = null;

            [$last, $noSurat, $penandatanganId, $statuses] = $this->buildStatusChain(
                $outcome, $base, $fo, $bo, $penandatanganIds
            );

            $izinId = DB::table('izin')->insertGetId([
                'jenis_perizinan_id' => $jenisIds[array_rand($jenisIds)],
                'pemohon_id' => $pemohonIds[array_rand($pemohonIds)],
                'penandatangan_id' => $penandatanganId,
                'no_pendaftaran' => $noStart + $i,
                'jenis_permohonan' => $this->pickJenisPermohonan(),
                'lokasi_izin' => random_int(0, 3)
                    ? $this->streets[array_rand($this->streets)] . ' Blok ' . chr(65 + random_int(0, 20)) . ' No. ' . random_int(1, 99)
                    : null,
                'no_surat' => $noSurat,
                'ambil_entri_lama' => false,
                'created_at' => $base,
                'updated_at' => $last,
            ]);

            $minIzinId = min($minIzinId, $izinId);
            $maxIzinId = max($maxIzinId, $izinId);

            foreach ($statuses as &$s) {
                $s['izin_id'] = $izinId;
                unset($s);
            }
            foreach ($statuses as $s) {
                $statusRows[] = $s;
            }
        }

        foreach (array_chunk($statusRows, 1000) as $chunk) {
            DB::table('status_izin')->insert($chunk);
        }
        unset($statusRows);

        $this->seedRiwayatProses($minIzinId, $maxIzinId);
    }

    /** @return array{Carbon, ?string, ?int, array} [updated_at, no_surat, penandatangan_id, status_rows] */
    protected function buildStatusChain(string $outcome, Carbon $base, int $fo, int $bo, array $penandatanganIds): array
    {
        $statuses = [];
        $last = $base;
        $noSurat = null;
        $penandatanganId = null;

        $push = function (string $nama, string $progres, ?int $pegawaiId, ?string $ket = null) use (&$statuses, &$last) {
            $last = $last->copy()->addHours(random_int(4, 72));
            if ($last->isFuture()) {
                $last = now()->subMinutes(random_int(5, 240));
            }
            $statuses[] = [
                'izin_id' => 0,
                'nama' => $nama,
                'progres' => $progres,
                'pegawai_id' => $pegawaiId,
                'keterangan' => $ket,
                'detail_keterangan' => null,
                'created_at' => $last->copy(),
                'updated_at' => $last->copy(),
            ];
        };

        switch ($outcome) {
            case 'finished':
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::DITINJAU, Progres::FINISHED, $fo);
                $push(Status::PROSES, Progres::PROCESSING, $bo, \App\utils\enums\BackOfficeStatusEnum::BELUM_ENTRI);
                $push(Status::PROSES, Progres::FINISHED, $bo, \App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI);
                $push(Status::PENERBITAN, Progres::PROCESSING, $bo);
                $push(Status::PENERBITAN, Progres::FINISHED, $bo);
                $push(Status::PENYERAHAN, Progres::PROCESSING, $fo);
                $push(Status::PENYERAHAN, Progres::FINISHED, $fo);
                $penandatanganId = $penandatanganIds ? $penandatanganIds[array_rand($penandatanganIds)] : null;
                $noSurat = $this->noSurat();
                break;

            case 'ditinjau':
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::DITINJAU, Progres::PROCESSING, $fo);
                break;

            case 'revisi':
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::DITINJAU, Progres::FINISHED, $fo);
                $push(Status::REVISI_PERSYARATAN, Progres::UNPROCESSED, $fo, 'Persyaratan belum lengkap — mohon unggah ulang dokumen.');
                break;

            case 'proses':
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::DITINJAU, Progres::FINISHED, $fo);
                $push(Status::PROSES, Progres::PROCESSING, $bo, \App\utils\enums\BackOfficeStatusEnum::BELUM_ENTRI);
                break;

            case 'penerbitan':
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::DITINJAU, Progres::FINISHED, $fo);
                $push(Status::PROSES, Progres::FINISHED, $bo, \App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI);
                $push(Status::PENERBITAN, Progres::PROCESSING, $bo);
                break;

            case 'siap_serah':
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::DITINJAU, Progres::FINISHED, $fo);
                $push(Status::PROSES, Progres::FINISHED, $bo, \App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI);
                $push(Status::PENERBITAN, Progres::FINISHED, $bo);
                $push(Status::PENYERAHAN, Progres::PROCESSING, $fo);
                $penandatanganId = $penandatanganIds ? $penandatanganIds[array_rand($penandatanganIds)] : null;
                $noSurat = $this->noSurat();
                break;

            case 'batal':
            default:
                $push(Status::DIDAFTARKAN, Progres::FINISHED, null);
                $push(Status::BATAL, Progres::CANCELED, $fo, 'Dibatalkan oleh pemohon.');
                break;
        }

        return [$last, $noSurat, $penandatanganId, $statuses];
    }

    protected function seedRiwayatProses(int $minIzinId, int $maxIzinId): void
    {
        $diproses = DB::table('status_izin')
            ->whereBetween('izin_id', [$minIzinId, $maxIzinId])
            ->where('nama', Status::PROSES)
            ->get(['id', 'izin_id', 'pegawai_id', 'progres']);

        if ($diproses->isEmpty()) {
            return;
        }

        $this->command->info("Menautkan {$diproses->count()} riwayat proses izin…");
        $rows = $diproses->map(fn ($s) => [
            'izin_id' => $s->izin_id,
            'status_izin_id' => $s->id,
            'pegawai_id' => $s->pegawai_id,
            'progres' => $s->progres,
            'keterangan' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('riwayat_proses_izin')->insert($chunk);
        }
    }

    /* -----------------------------------------------------------------
     |  Helpers
     * ----------------------------------------------------------------- */

    protected function pickOutcome(): string
    {
        $r = random_int(1, 100);
        if ($r <= 50) return 'finished';
        if ($r <= 62) return 'ditinjau';
        if ($r <= 72) return 'revisi';
        if ($r <= 85) return 'proses';
        if ($r <= 93) return 'penerbitan';
        if ($r <= 97) return 'siap_serah';

        return 'batal';
    }

    protected function pickJenisPermohonan(): string
    {
        $r = random_int(1, 100);
        if ($r <= 85) return 'baru';
        if ($r <= 95) return 'perpanjangan';

        return 'perubahan';
    }

    protected function nama(): string
    {
        return $this->firstNames[array_rand($this->firstNames)] . ' ' .
               $this->lastNames[array_rand($this->lastNames)] . ' ' .
               $this->lastNames[array_rand($this->lastNames)];
    }

    protected function nik(): string
    {
        // 16 digit unik per run: '75' + yy + mmdd + salt(4) + serial(4)
        static $serial = 0;
        $serial++;

        return '75' . date('y') . date('md') . $this->salt . str_pad((string) $serial, 4, '0', STR_PAD_LEFT);
    }

    protected function noSurat(): string
    {
        static $seq = 0;
        $seq++;

        return '500/' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT) . '/BBL/' . date('ym');
    }

    protected function ensurePegawai(): array
    {
        $now = now();
        $withUser = DB::table('pegawai')->whereNotNull('user_id')->whereNull('deleted_at')->pluck('id')->all();

        if (empty($withUser)) {
            DB::table('pegawai')->insert([
                ['user_id' => null, 'nama' => 'Front Office Dummy', 'jabatan' => 'front office', 'pangkat' => 'Penata', 'nip' => '197001012000121001', 'gender' => 'male', 'created_at' => $now, 'updated_at' => $now],
                ['user_id' => null, 'nama' => 'Back Office Dummy', 'jabatan' => 'back office', 'pangkat' => 'Penata Muda', 'nip' => '198505052010011002', 'gender' => 'female', 'created_at' => $now, 'updated_at' => $now],
            ]);
            $withUser = DB::table('pegawai')->whereNull('deleted_at')->pluck('id')->all();
        }

        $penandatangan = DB::table('pegawai')
            ->whereNull('deleted_at')->whereNull('user_id')
            ->whereIn('jabatan', ['kepala dinas', 'kepala bidang'])
            ->pluck('id')->all();

        if (empty($penandatangan)) {
            DB::table('pegawai')->insert([
                ['user_id' => null, 'nama' => 'Kepala Dinas Dummy', 'jabatan' => 'kepala dinas', 'pangkat' => 'Pembina IV/a', 'nip' => '196505152003121003', 'gender' => 'male', 'created_at' => $now, 'updated_at' => $now],
                ['user_id' => null, 'nama' => 'Kabid Perizinan Dummy', 'jabatan' => 'kepala bidang', 'pangkat' => 'Pembina III/d', 'nip' => '197203102006042004', 'gender' => 'female', 'created_at' => $now, 'updated_at' => $now],
            ]);
            $penandatangan = DB::table('pegawai')
                ->whereNull('deleted_at')->whereNull('user_id')
                ->whereIn('jabatan', ['kepala dinas', 'kepala bidang'])
                ->pluck('id')->all();
        }

        return [$withUser, $withUser, $penandatangan];
    }

    protected function ensureDummyAvatars(): void
    {
        $target = public_path('storage/users');
        if (! File::exists($target)) {
            File::makeDirectory($target, 0755, true);
        }
        foreach (['male', 'female'] as $g) {
            $src = public_path("storage/web/{$g}.png");
            $dst = "{$target}/dummy-{$g}.png";
            if (File::exists($src) && ! File::exists($dst)) {
                File::copy($src, $dst);
            }
        }
    }

    protected function speedUp(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('SET unique_checks=0');
        }
    }

    protected function restoreSpeed(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET unique_checks=1');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
