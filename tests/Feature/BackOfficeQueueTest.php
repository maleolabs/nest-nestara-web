<?php

namespace Tests\Feature;

use App\Models\Izin;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BackOfficeQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_recommendation_survives_reload_into_penetapan_queue(): void
    {
        $jenisId = DB::table('jenis_perizinan')->insertGetId([
            'nama' => 'Izin test', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $pemohonId = DB::table('pemohon')->insertGetId([
            'sumber_identitas' => 'KTP', 'nomor_id' => '1234567890123456', 'nama_pemohon' => 'Pemohon test',
            'no_telepon' => '08123456789', 'provinsi' => 1, 'kabupaten' => 1, 'kecamatan' => 1, 'kelurahan' => 1,
            'detail_alamat' => 'Test', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $izin = Izin::create(['jenis_perizinan_id' => $jenisId, 'pemohon_id' => $pemohonId, 'no_pendaftaran' => 1]);
        DB::table('status_izin')->insert([
            'izin_id' => $izin->id, 'nama' => StatusIzinEnum::PROSES,
            'progres' => StatusIzinProgressEnum::PROCESSING,
            'keterangan' => BackOfficeStatusEnum::DIREKOM,
            'detail_keterangan' => 'pemeriksaan berkas', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $rows = app(PerizinanService::class)->getAllBySprecificFilter(
            filter_status: [StatusIzinEnum::PROSES],
            filter_status_progress: [StatusIzinProgressEnum::PROCESSING, StatusIzinProgressEnum::FINISHED],
            filter_keterangan: [BackOfficeStatusEnum::DIREKOM, BackOfficeStatusEnum::REKOM_CATATAN, BackOfficeStatusEnum::BAHAS_LANJUT, BackOfficeStatusEnum::DICETAK],
            filter_start_date: now()->subDay()->toDateString(), filter_end_date: now()->toDateString(),
        );

        self::assertTrue(collect($rows)->contains('id', $izin->id));
        self::assertSame(BackOfficeStatusEnum::DIREKOM, $izin->fresh()->latestStatus->keterangan);
    }
}
