<?php

namespace Tests\Feature\DomainService;

use App\Http\Resources\IzinResource;
use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pemohon;
use App\Models\StatusIzin;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StatusIzinResourceTest extends TestCase
{
    /** Status terakhir penyerahan yang sudah final HARUS membawa progres 'selesai'. */
    public function test_izin_status_payload_exposes_progres_for_finished_handover(): void
    {
        $izin = Izin::create([
            'jenis_perizinan_id' => JenisPerizinan::create(['nama' => 'IZIN RESOURCE TEST'])->id,
            'pemohon_id' => Pemohon::factory()->create()->id,
            'no_pendaftaran' => 200,
        ]);
        StatusIzin::create([
            'izin_id' => $izin->id,
            'nama' => StatusIzinEnum::PENYERAHAN,
            'progres' => StatusIzinProgressEnum::PROCESSING,
            'keterangan' => 'siap diambil',
        ]);
        StatusIzin::create([
            'izin_id' => $izin->id,
            'nama' => StatusIzinEnum::PENYERAHAN,
            'progres' => StatusIzinProgressEnum::FINISHED,
            'keterangan' => BackOfficeStatusEnum::IZIN_TELAH_DISERAHKAN,
        ]);

        // kontrak yang dipakai endpoint GET /api/izin/{id}
        $payload = (new IzinResource($izin->fresh()->load('jenis_perizinan', 'status_izin')))
            ->resolve(request());

        $latest = collect($payload['status'])->last();
        $this->assertSame(StatusIzinEnum::PENYERAHAN, $latest['nama']);
        $this->assertSame(StatusIzinProgressEnum::FINISHED, $payload['status'][1]['progres']);
        $this->assertArrayHasKey('progres', $latest);
    }

    /** Versi HTTP: response GET /api/izin/{id} memuat progres 'selesai' di status terakhir. */
    public function test_api_find_izin_status_contains_progres_selesai(): void
    {
        $user = \App\Models\User::create(['username' => 'resource-test', 'password' => 'Password#123']);
        $pemohon = Pemohon::factory()->create(['user_id' => $user->id]);
        $izin = Izin::create([
            'jenis_perizinan_id' => JenisPerizinan::create(['nama' => 'IZIN API RESOURCE TEST'])->id,
            'pemohon_id' => $pemohon->id,
            'no_pendaftaran' => 201,
        ]);
        StatusIzin::create([
            'izin_id' => $izin->id,
            'nama' => StatusIzinEnum::PENYERAHAN,
            'progres' => StatusIzinProgressEnum::FINISHED,
            'keterangan' => BackOfficeStatusEnum::IZIN_TELAH_DISERAHKAN,
        ]);

        // Kontrak end-to-end lewat route asli (guard JWT).
        // Catatan: response tidak dibungkus 'data' karena controller pass
        // resource langsung ke response()->json().
        $this->withToken(\Tymon\JWTAuth\Facades\JWTAuth::fromUser($user))
            ->getJson("/api/izin/{$izin->id}")
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('status.0.progres', StatusIzinProgressEnum::FINISHED)
                    ->where('status.0.keterangan', BackOfficeStatusEnum::IZIN_TELAH_DISERAHKAN)
                    ->etc(),
            );
    }
}
