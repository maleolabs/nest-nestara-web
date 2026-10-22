<?php

namespace Tests\Feature;

use App\Jobs\GeneratePermitLetter;
use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pegawai;
use App\Models\PermitLetterGeneration;
use App\Models\Pemohon;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\StatusIzin;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use App\utils\enums\BackOfficeStatusEnum;
use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class PermitLetterGenerationTest extends TestCase
{
    use RefreshDatabase;

    private function izin(): Izin
    {
        return Izin::create([
            'jenis_perizinan_id' => JenisPerizinan::create(['nama' => 'Test'])->id,
            'pemohon_id' => Pemohon::create(['sumber_identitas' => 'ktp', 'nomor_id' => '1', 'nama_pemohon' => 'Test', 'no_telepon' => '1', 'provinsi' => 1, 'kabupaten' => 1, 'kecamatan' => 1, 'kelurahan' => 1, 'detail_alamat' => 'x'])->id,
            'no_pendaftaran' => 1,
        ]);
    }

    private function eligible(Izin $izin): void
    {
        StatusIzin::create(['izin_id' => $izin->id, 'nama' => StatusIzinEnum::PROSES, 'progres' => StatusIzinProgressEnum::PROCESSING, 'keterangan' => BackOfficeStatusEnum::DIIZINKAN]);
    }

    private function services(Izin $izin, bool $complete = true): array
    {
        $perizinan = Mockery::mock(PerizinanService::class);
        $perizinan->shouldReceive('find')->andReturn($izin);
        $perizinan->shouldReceive('updateCetakIzinStatus')->zeroOrMoreTimes();
        $jenis = Mockery::mock(JenisPerizinanService::class);
        $jenis->shouldReceive('getPdfTemplate')->andReturn($complete ? 'pdf.test' : null);
        $entri = Mockery::mock(EntriDataService::class);
        $entri->shouldReceive('getDataIzinGroupByNama')->andReturn($complete ? ['x'] : []);
        $encryption = Mockery::mock(EncryptionService::class);
        $encryption->shouldReceive('encryptId')->andReturn('x');
        $surat = Mockery::mock(SuratService::class);
        $surat->shouldReceive('getKopSurat')->andReturn([]);
        $surat->shouldReceive('getSuratContentByJenis')->andReturn([]);
        return [$perizinan, $encryption, $jenis, $entri, $surat];
    }

    private function backOfficeUser(): User
    {
        Role::firstOrCreate(['name' => BACK_OFFICE_ROLE, 'guard_name' => 'web']);
        $user = User::create(['name' => 'Back Office', 'username' => 'backoffice', 'email' => 'bo@example.test', 'password' => Hash::make('password')]);
        $user->assignRole(BACK_OFFICE_ROLE);
        return $user;
    }

    public function test_job_persists_artifact_before_completion(): void
    {
        Storage::fake('local');
        $pdf = Mockery::mock(DomPdf::class);
        $pdf->shouldReceive('output')->andReturn('pdf');
        Pdf::shouldReceive('loadView')->andReturn($pdf);
        $izin = $this->izin();
        $this->eligible($izin);
        $generation = PermitLetterGeneration::create(['izin_id' => $izin->id, 'status' => PermitLetterGeneration::QUEUED]);
        [$p, $e, $j, $d, $s] = $this->services($izin);

        (new GeneratePermitLetter($generation->id))->handle($p, $e, $j, $d, $s);
        $generation->refresh();
        $this->assertSame(PermitLetterGeneration::COMPLETED, $generation->status);
        $this->assertNotNull($generation->path);
        Storage::disk('local')->assertExists($generation->path);
    }

    public function test_failure_requeues_then_failed_and_failed_callback_is_final(): void
    {
        $izin = $this->izin();
        $this->eligible($izin);
        $generation = PermitLetterGeneration::create(['izin_id' => $izin->id, 'status' => PermitLetterGeneration::QUEUED]);
        [$p, $e, $j, $d, $s] = $this->services($izin, false);
        try { (new GeneratePermitLetter($generation->id))->handle($p, $e, $j, $d, $s); } catch (\Throwable) {}
        $this->assertSame(PermitLetterGeneration::QUEUED, $generation->refresh()->status);
        $generation->update(['attempts' => 3, 'status' => PermitLetterGeneration::PROCESSING]);
        try { (new GeneratePermitLetter($generation->id))->handle($p, $e, $j, $d, $s); } catch (\Throwable) {}
        $this->assertSame(PermitLetterGeneration::FAILED, $generation->refresh()->status);
    }

    public function test_duplicate_dispatch_is_serialized_by_existing_generation(): void
    {
        Queue::fake();
        $izin = $this->izin();
        $generation = PermitLetterGeneration::create(['izin_id' => $izin->id, 'status' => PermitLetterGeneration::QUEUED]);
        Queue::assertNothingPushed();
        $this->assertSame($generation->id, PermitLetterGeneration::where('izin_id', $izin->id)->where('status', PermitLetterGeneration::QUEUED)->first()->id);
    }

    public function test_job_ignores_completed_generation(): void
    {
        $izin = $this->izin();
        $generation = PermitLetterGeneration::create(['izin_id' => $izin->id, 'status' => PermitLetterGeneration::COMPLETED, 'path' => 'x']);
        $job = new GeneratePermitLetter($generation->id);
        $job->handle(Mockery::mock(PerizinanService::class), Mockery::mock(EncryptionService::class), Mockery::mock(JenisPerizinanService::class), Mockery::mock(EntriDataService::class), Mockery::mock(SuratService::class));
        $this->assertSame(0, $generation->refresh()->attempts);
    }

    public function test_controller_dispatches_only_for_eligible_back_office_user_and_reuses_active_generation(): void
    {
        Queue::fake();
        $izin = $this->izin();
        $this->eligible($izin);
        $this->app->instance(EncryptionService::class, Mockery::mock(EncryptionService::class, function ($m) use ($izin) { $m->shouldReceive('decryptId')->with('encrypted')->andReturn($izin->id); }));
        $this->actingAs($this->backOfficeUser());

        $first = $this->postJson(route('pegawai.download-surat'), ['id' => 'encrypted']);
        $first->assertAccepted()->assertJsonPath('status', PermitLetterGeneration::QUEUED);
        Queue::assertPushed(GeneratePermitLetter::class);
        $second = $this->postJson(route('pegawai.download-surat'), ['id' => 'encrypted']);
        $second->assertAccepted()->assertJsonPath('id', $first->json('id'));
        $this->assertDatabaseCount('permit_letter_generations', 1);
    }

    public function test_controller_rejects_ineligible_and_non_back_office_users(): void
    {
        $izin = $this->izin();
        $this->app->instance(EncryptionService::class, Mockery::mock(EncryptionService::class, function ($m) use ($izin) { $m->shouldReceive('decryptId')->andReturn($izin->id); }));
        $this->actingAs($this->backOfficeUser())->postJson(route('pegawai.download-surat'), ['id' => 'x'])->assertStatus(422);
        Role::firstOrCreate(['name' => FRONT_OFFICE_ROLE, 'guard_name' => 'web']);
        $front = User::create(['name' => 'Front', 'username' => 'front', 'email' => 'front@example.test', 'password' => Hash::make('password')]);
        $front->assignRole(FRONT_OFFICE_ROLE);
        $this->actingAs($front)->postJson(route('pegawai.download-surat'), ['id' => 'x'])->assertForbidden();
        $this->getJson(route('pegawai.surat.status', 999))->assertForbidden();
    }

    public function test_status_and_download_require_completed_existing_file(): void
    {
        Storage::fake('local');
        $izin = $this->izin();
        $generation = PermitLetterGeneration::create(['izin_id' => $izin->id, 'status' => PermitLetterGeneration::COMPLETED, 'path' => 'permit-letters/x.pdf']);
        $this->actingAs($this->backOfficeUser())->getJson(route('pegawai.surat.status', $generation->id))->assertOk()->assertJsonPath('status', 'completed');
        $this->get(route('pegawai.surat.download', $generation->id))->assertNotFound();
        Storage::disk('local')->put($generation->path, 'pdf');
        $this->get(route('pegawai.surat.download', $generation->id))->assertOk()->assertDownload('IZIN-'.$izin->id.'.pdf');
        $generation->update(['status' => PermitLetterGeneration::FAILED, 'path' => null]);
        $this->get(route('pegawai.surat.download', $generation->id))->assertNotFound();
    }
}
