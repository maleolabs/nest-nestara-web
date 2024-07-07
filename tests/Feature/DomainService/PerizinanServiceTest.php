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
        DB::statement("SET foreign_key_checks=0");
        Pemohon::truncate();
        Izin::truncate();
        StatusIzin::truncate();
        DB::statement("SET foreign_key_checks=1");

        DB::statement("ALTER TABLE pemohon MODIFY no_telepon VARCHAR(20)");

        $pemohon = Pemohon::factory()->create();
        assertNotNull($pemohon);
        $pemohon2 = Pemohon::factory()->create();

        $izin_penmas = JenisPerizinan::where('nama', 'IZIN PENELITIAN MAHASISWA')->first();

        $izin = $this->perizinanService()->save($izin_penmas->id, $pemohon->id);
        assertSame($izin->pemohon_id, $pemohon->id);
        $izin2 = $this->perizinanService()->save($izin_penmas->id, $pemohon2->id);

        $saved = $this->perizinanService()->saveRegistrationAction($izin->id, now(), fake()->company(), null, true);
        assertTrue($saved);
        $this->perizinanService()->saveRegistrationAction($izin2->id, now(), fake()->company(), null, true);

        $this->perizinanService()->updateIzinStatusThatHaveBeenReviewed();

        $all_reviewed = $this->perizinanService()->getAll('', [StatusIzinEnum::PROSES], [StatusIzinProgressEnum::UNPROCESSED]);
        assertSame(2, count($all_reviewed));
    }
 
    private function perizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

}