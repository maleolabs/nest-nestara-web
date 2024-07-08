<?php

namespace Tests\Feature\DomainService;

use App\Models\EntriData;
use App\Models\EntriDataIzin;
use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pemohon;
use App\Models\StatusIzin;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use App\Services\DomainServices\EntriDataService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class EntriDataServiceTest extends TestCase
{

    public function test_is_entri_data_izin_exists_by_izin_and_jenis_success(): void
    {
        DB::statement("SET foreign_key_checks=0");
        Pemohon::truncate();
        Izin::truncate();
        EntriData::truncate();
        EntriDataIzin::truncate();
        DB::statement("SET foreign_key_checks=1");

        DB::statement("ALTER TABLE pemohon MODIFY no_telepon VARCHAR(20)");

        Artisan::call('db:seed', ['--class' => 'EntriPenMasSeeder']);

        $pemohon = Pemohon::factory()->create();
        assertNotNull($pemohon);

        $izin_penmas = JenisPerizinan::where('nama', 'IZIN PENELITIAN MAHASISWA')->first();

        $izin = $this->perizinanService()->save($izin_penmas->id, $pemohon->id);
        assertSame($izin->pemohon_id, $pemohon->id);

        $saved = $this->perizinanService()->saveRegistrationAction($izin->id, now(), fake()->company(), null, true);
        assertTrue($saved);

        $entri_data = $this->entriDataService()->getByJenisPerizinanId($izin_penmas->id);
        assertNotEmpty($entri_data);
        foreach ($entri_data as $entri) {
            $value = $entri->tipe == 'date' ? Carbon::today()->toDateString() : "dummy value of {$entri->nama}";
            $this->entriDataService()->saveEntriDataIzin($entri->id, $izin->id, $value);
        }

        assertTrue($this->entriDataService()->isEntriDataIzinExistsByIzinAndJenis($izin->id, $izin_penmas->id));
    }
    
    public function test_is_entri_data_izin_exists_by_izin_and_jenis_failed(): void
    {
        DB::statement("SET foreign_key_checks=0");
        Pemohon::truncate();
        Izin::truncate();
        EntriData::truncate();
        EntriDataIzin::truncate();
        DB::statement("SET foreign_key_checks=1");

        DB::statement("ALTER TABLE pemohon MODIFY no_telepon VARCHAR(20)");

        Artisan::call('db:seed', ['--class' => 'EntriPenMasSeeder']);

        $pemohon = Pemohon::factory()->create();
        assertNotNull($pemohon);

        $izin_penmas = JenisPerizinan::where('nama', 'IZIN PENELITIAN MAHASISWA')->first();

        $izin = $this->perizinanService()->save($izin_penmas->id, $pemohon->id);
        assertSame($izin->pemohon_id, $pemohon->id);

        $saved = $this->perizinanService()->saveRegistrationAction($izin->id, now(), fake()->company(), null, true);
        assertTrue($saved);

        $entri_data = $this->entriDataService()->getByJenisPerizinanId($izin_penmas->id);
        foreach ($entri_data as $entri) {
            $value = $entri->tipe == 'date' ? Carbon::today()->toDateString() : "dummy value of {$entri->nama}";
            $this->entriDataService()->saveEntriDataIzin($entri->id, $izin->id, $value);
        }

        Artisan::call('db:seed', ['--class' => 'EntriPenMasSeeder']);

        assertFalse($this->entriDataService()->isEntriDataIzinExistsByIzinAndJenis($izin->id, $izin_penmas->id));
    }

    private function entriDataService(): EntriDataService
    {
        return app(EntriDataService::class);
    }

    private function perizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}