<?php

namespace Tests\Feature\DomainService;

use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pemohon;
use App\Models\PermitLetterGeneration;
use App\Models\StatusIzin;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class PerizinanServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_update_izin_status_that_have_been_reviewed(): void
    {
        Schema::disableForeignKeyConstraints();
        Pemohon::truncate();
        Izin::truncate();
        StatusIzin::truncate();
        Schema::enableForeignKeyConstraints();


        $pemohon = Pemohon::factory()->create();
        assertNotNull($pemohon);
        $pemohon2 = Pemohon::factory()->create();

        $izin_penmas = JenisPerizinan::create(['nama' => 'IZIN PENELITIAN MAHASISWA']);

        $izin = $this->perizinanService()->save($izin_penmas->id, $pemohon->id);
        assertSame($izin->pemohon_id, $pemohon->id);
        $izin2 = $this->perizinanService()->save($izin_penmas->id, $pemohon2->id);

        $saved = $this->perizinanService()->saveRegistrationAction($izin->id, now(), fake()->company(), 'permohonan-selesai', null);
        assertTrue($saved);
        $this->perizinanService()->saveRegistrationAction($izin2->id, now(), fake()->company(), 'permohonan-selesai', null);

        $this->perizinanService()->updateIzinStatusThatHaveBeenReviewed();

        $all_reviewed = $this->perizinanService()->getAll('', [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::UNPROCESSED]);
        assertSame(2, count($all_reviewed));
    }

    public function test_cancel_registration_persists_batal_as_latest_status(): void
    {
        Schema::disableForeignKeyConstraints();
        Pemohon::truncate();
        Izin::truncate();
        StatusIzin::truncate();
        Schema::enableForeignKeyConstraints();

        $pemohon = Pemohon::factory()->create();
        $jenis = JenisPerizinan::create(['nama' => 'IZIN CANCEL TEST']);
        $izin = $this->perizinanService()->save($jenis->id, $pemohon->id);

        $this->assertTrue($this->perizinanService()->cancelRegistration($izin->id));

        $this->assertSame(StatusIzinEnum::BATAL, $izin->fresh()->latestStatus->nama);
    }

    public function test_handover_query_returns_two_permits_with_completed_letters(): void
    {
        $jenis = JenisPerizinan::create(['nama' => 'IZIN HANDOVER TEST']);

        foreach (range(1, 2) as $number) {
            $izin = Izin::create([
                'jenis_perizinan_id' => $jenis->id,
                'pemohon_id' => Pemohon::create([
                    'sumber_identitas' => 'ktp',
                    'nomor_id' => "handover-{$number}",
                    'nama_pemohon' => "Handover {$number}",
                    'no_telepon' => '081234567890',
                    'provinsi' => 1,
                    'kabupaten' => 1,
                    'kecamatan' => 1,
                    'kelurahan' => 1,
                    'detail_alamat' => 'Test address',
                ])->id,
                'no_pendaftaran' => $number,
            ]);
            StatusIzin::create([
                'izin_id' => $izin->id,
                'nama' => StatusIzinEnum::PROSES,
                'progres' => StatusIzinProgressEnum::PROCESSING,
                'keterangan' => 'diizinkan',
            ]);
            PermitLetterGeneration::create([
                'izin_id' => $izin->id,
                'status' => PermitLetterGeneration::COMPLETED,
                'path' => "permit-letters/{$number}.pdf",
            ]);
        }

        $permits = $this->perizinanService()->get(
            filter_status: [StatusIzinEnum::PROSES, StatusIzinEnum::PENERBITAN, StatusIzinEnum::PENYERAHAN],
            filter_status_progress: [StatusIzinProgressEnum::UNPROCESSED, StatusIzinProgressEnum::PROCESSING, StatusIzinProgressEnum::FINISHED],
            filter_start_date: now()->subDay()->toDateString(),
            filter_end_date: now()->toDateString(),
            handoverPrintedOnly: true,
        );

        $this->assertCount(2, $permits);
    }

    public function test_handover_query_includes_old_completed_permit_without_date_filter(): void
    {
        $jenis = JenisPerizinan::create(['nama' => 'IZIN OLD HANDOVER TEST']);
        $izin = Izin::create([
            'jenis_perizinan_id' => $jenis->id,
            'pemohon_id' => Pemohon::factory()->create()->id,
            'no_pendaftaran' => 99,
            'created_at' => now()->subMonths(2),
        ]);
        StatusIzin::create([
            'izin_id' => $izin->id,
            'nama' => StatusIzinEnum::PROSES,
            'progres' => StatusIzinProgressEnum::PROCESSING,
            'keterangan' => 'diizinkan',
        ]);
        PermitLetterGeneration::create([
            'izin_id' => $izin->id,
            'status' => PermitLetterGeneration::COMPLETED,
            'path' => 'permit-letters/old.pdf',
        ]);

        $permits = $this->perizinanService()->get(
            filter_status: [StatusIzinEnum::PROSES, StatusIzinEnum::PENERBITAN, StatusIzinEnum::PENYERAHAN],
            filter_status_progress: [StatusIzinProgressEnum::UNPROCESSED, StatusIzinProgressEnum::PROCESSING, StatusIzinProgressEnum::FINISHED],
            handoverPrintedOnly: true,
        );

        $this->assertTrue($permits->contains('id', $izin->id));
    }
 
    private function perizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

}
