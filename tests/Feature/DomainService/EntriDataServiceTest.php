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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        Schema::disableForeignKeyConstraints();
        Pemohon::truncate();
        Izin::truncate();
        EntriData::truncate();
        EntriDataIzin::truncate();
        Schema::enableForeignKeyConstraints();


        $izin_penmas = JenisPerizinan::create(['nama' => 'IZIN PENELITIAN MAHASISWA']);
        EntriData::create(['jenis_perizinan_id' => $izin_penmas->id, 'nama' => 'judul penelitian', 'tipe' => 'text', 'mandatori' => true, 'max' => 100]);

        $pemohon = Pemohon::factory()->create();
        assertNotNull($pemohon);

        $izin = $this->perizinanService()->save($izin_penmas->id, $pemohon->id);
        assertSame($izin->pemohon_id, $pemohon->id);

        $saved = $this->perizinanService()->saveRegistrationAction($izin->id, now()->toDateTimeString(), fake()->company(), 'permohonan-selesai');
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
        Schema::disableForeignKeyConstraints();
        Pemohon::truncate();
        Izin::truncate();
        EntriData::truncate();
        EntriDataIzin::truncate();
        Schema::enableForeignKeyConstraints();


        $izin_penmas = JenisPerizinan::create(['nama' => 'IZIN PENELITIAN MAHASISWA']);
        EntriData::create(['jenis_perizinan_id' => $izin_penmas->id, 'nama' => 'judul penelitian', 'tipe' => 'text', 'mandatori' => true, 'max' => 100]);

        $pemohon = Pemohon::factory()->create();
        assertNotNull($pemohon);

        $izin = $this->perizinanService()->save($izin_penmas->id, $pemohon->id);
        assertSame($izin->pemohon_id, $pemohon->id);

        $saved = $this->perizinanService()->saveRegistrationAction($izin->id, now()->toDateTimeString(), fake()->company(), 'permohonan-selesai');
        assertTrue($saved);

        $entri_data = $this->entriDataService()->getByJenisPerizinanId($izin_penmas->id);
        foreach ($entri_data as $entri) {
            $value = $entri->tipe == 'date' ? Carbon::today()->toDateString() : "dummy value of {$entri->nama}";
            $this->entriDataService()->saveEntriDataIzin($entri->id, $izin->id, $value);
        }

        EntriData::where('jenis_perizinan_id', $izin_penmas->id)->delete();
        EntriDataIzin::where('izin_id', $izin->id)->delete();
        EntriData::create(['jenis_perizinan_id' => $izin_penmas->id, 'nama' => 'judul penelitian baru', 'tipe' => 'text', 'mandatori' => true, 'max' => 100]);

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
