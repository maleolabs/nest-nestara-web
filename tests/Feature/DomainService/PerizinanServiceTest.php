<?php

namespace Tests\Feature\DomainService;

use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pemohon;
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
 
    private function perizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

}
