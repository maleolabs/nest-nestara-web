<?php

namespace Tests\Feature\Api;

use App\Models\OcrKtpRequest as IdentityDocument;
use App\Models\User;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\IdentityDocumentAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * E2E alur identity: upload → polling → ready dengan field hasil OCR pass tunggal.
 *
 * Vision 0.11+ menyimpan hasil parse pass verifikasi dan mengembalikannya lewat
 * endpoint status. Web tidak boleh memanggil /ocr/do-ocr sama sekali untuk
 * alur ini.
 */
final class IdentityDocumentE2ETest extends TestCase
{
    use RefreshDatabase;

    private const CHECK_ID = '77777777-7777-4777-8777-777777777777';

    private const KTP_EXTRACTED_FIELDS = [
        ['textKey' => 'provinsi', 'textValue' => 'GORONTALO'],
        ['textKey' => 'kabupaten', 'textValue' => 'KABUPATEN GORONTALO'],
        ['textKey' => 'nik', 'textValue' => '1234567890123456'],
        ['textKey' => 'nama', 'textValue' => 'BUDI SANTOSO'],
        ['textKey' => 'tempat/tgl lahir', 'textValue' => 'GORONTALO, 01-01-1990'],
        ['textKey' => 'jenis kelamin', 'textValue' => 'LAKI-LAKI'],
        ['textKey' => 'alamat', 'textValue' => 'JL MERDEKA'],
        ['textKey' => 'rt/rw', 'textValue' => '001/002'],
        ['textKey' => 'kelurahan', 'textValue' => 'BULOTEA'],
        ['textKey' => 'kecamatan', 'textValue' => 'BONE'],
        ['textKey' => 'agama', 'textValue' => 'ISLAM'],
        ['textKey' => 'status perkawinan', 'textValue' => 'BELUM KAWIN'],
        ['textKey' => 'pekerjaan', 'textValue' => 'PELAJAR'],
        ['textKey' => 'kewarganegaraan', 'textValue' => 'WNI'],
        ['textKey' => 'berlaku hingga', 'textValue' => 'SEUMUR HIDUP'],
    ];

    private function mockVision(bool $accepted): IdentityDocumentAPIService
    {
        $vision = Mockery::mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('check')->once()->andReturn(new OcrResponse([
            'requestId' => 'req-e2e',
            'status' => 'PROCESSING',
        ], true, null));
        $vision->shouldReceive('status')->andReturn(new OcrResponse([
            'requestId' => 'req-e2e',
            'status' => 'DONE',
            'decision' => $accepted ? 'ACCEPTED' : 'MANUAL_REVIEW',
            'documentType' => 'KTP',
            'reasonCode' => $accepted ? 'DOCUMENT_VALID' : 'DOCUMENT_AMBIGUOUS',
            'extractedFields' => self::KTP_EXTRACTED_FIELDS,
        ], true, null));
        $this->app->instance(IdentityDocumentAPIService::class, $vision);

        return $vision;
    }

    public function test_full_flow_upload_poll_ready_fields_autofill(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-e2e-ready']);
        $this->mockVision(accepted: true);

        // 1. Upload
        $store = $this->withToken(JWTAuth::fromUser($user))
            ->postJson('/api/identity-documents', [
                'image' => UploadedFile::fake()->image('ktp.jpg'),
                'documentType' => 'KTP',
            ]);
        $store->assertAccepted()->assertJsonPath('status', 'verifying');
        $checkId = IdentityDocument::where('user_id', $user->id)->value('public_check_id');
        $this->assertNotNull($checkId);

        // 2. Polling pertama: Vision selesai, field sudah tersedia
        $response = $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$checkId);
        $response->assertOk()
            ->assertJsonPath('status', 'ready')
            ->assertJsonPath('ready', true)
            ->assertJsonPath('profile_unlocked', true)
            ->assertJsonPath('document_type', 'KTP')
            ->assertJsonPath('data.nik', '1234567890123456')
            ->assertJsonPath('data.nama', 'BUDI SANTOSO')
            ->assertJsonPath('data.provinsi', 'GORONTALO')
            ->assertJsonPath('data.kabupaten', 'KABUPATEN GORONTALO')
            ->assertJsonPath('data.kecamatan', 'BONE')
            ->assertJsonPath('data.kelurahan', 'BULOTEA')
            ->assertJsonPath('data.tempat_tgl_lahir', 'GORONTALO, 01-01-1990');

        $this->assertDatabaseHas('ocr_ktp_requests', [
            'public_check_id' => $checkId,
            'status' => 'ready',
            'extraction_request_id' => null,
            'file_path' => null,
        ]);
    }

    public function test_full_flow_manual_review_stays_locked(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-e2e-review']);
        $this->mockVision(accepted: false);

        $this->withToken(JWTAuth::fromUser($user))
            ->postJson('/api/identity-documents', [
                'image' => UploadedFile::fake()->image('ktp.jpg'),
                'documentType' => 'KTP',
            ])->assertAccepted();

        $checkId = IdentityDocument::where('user_id', $user->id)->value('public_check_id');

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$checkId)
            ->assertOk()
            ->assertJsonPath('status', 'manual_review')
            ->assertJsonPath('profile_unlocked', false)
            ->assertJsonPath('ready', false)
            ->assertJsonPath('reason_code', 'DOCUMENT_AMBIGUOUS');
    }

    public function test_full_flow_sim_accepted_fields_map_to_nomor_id(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-e2e-sim']);
        $vision = Mockery::mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('check')->once()->andReturn(new OcrResponse([
            'requestId' => 'req-e2e-sim', 'status' => 'PROCESSING',
        ], true, null));
        // Vision memfilter field SIM terhadap requiredKeys KTP; `nomor` khas SIM
        // tidak ada di whitelist tetapi fuzzy levenshtein <=3 memetakannya ke `nik`
        // sehingga mobile tetap dapat nomor SIM pada field nomor identitas.
        $vision->shouldReceive('status')->once()->andReturn(new OcrResponse([
            'status' => 'DONE',
            'decision' => 'ACCEPTED',
            'documentType' => 'SIM',
            'reasonCode' => 'DOCUMENT_VALID',
            'extractedFields' => [
                ['textKey' => 'nama', 'textValue' => 'BUDI SANTOSO'],
                ['textKey' => 'nik', 'textValue' => '9876543210'],
                ['textKey' => 'alamat', 'textValue' => 'JL MERDEKA'],
            ],
        ], true, null));
        $this->app->instance(IdentityDocumentAPIService::class, $vision);

        $this->withToken(JWTAuth::fromUser($user))
            ->postJson('/api/identity-documents', [
                'image' => UploadedFile::fake()->image('sim.jpg'),
                'documentType' => 'SIM',
            ])->assertAccepted();
        $checkId = IdentityDocument::where('user_id', $user->id)->value('public_check_id');

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$checkId)
            ->assertOk()
            ->assertJsonPath('status', 'ready')
            ->assertJsonPath('profile_unlocked', true)
            ->assertJsonPath('document_type', 'SIM')
            ->assertJsonPath('data.nama', 'BUDI SANTOSO')
            ->assertJsonPath('data.nik', '9876543210');
    }

    public function test_full_flow_vision_unavailable_then_recovers_on_next_poll(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-e2e-flap']);
        $vision = Mockery::mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('check')->once()->andReturn(new OcrResponse([
            'requestId' => 'req-e2e-flap', 'status' => 'PROCESSING',
        ], true, null));
        $vision->shouldReceive('status')->once()->andReturn(OcrResponse::failure('vision_unavailable'));
        $vision->shouldReceive('status')->once()->andReturn(new OcrResponse([
            'status' => 'DONE',
            'decision' => 'ACCEPTED',
            'documentType' => 'KTP',
            'extractedFields' => self::KTP_EXTRACTED_FIELDS,
        ], true, null));
        $this->app->instance(IdentityDocumentAPIService::class, $vision);

        $this->withToken(JWTAuth::fromUser($user))
            ->postJson('/api/identity-documents', [
                'image' => UploadedFile::fake()->image('ktp.jpg'),
            ])->assertAccepted();
        $checkId = IdentityDocument::where('user_id', $user->id)->value('public_check_id');

        // 502 pertama: status tetap verifying, bisa di-poll ulang
        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$checkId)
            ->assertStatus(502);

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$checkId)
            ->assertOk()
            ->assertJsonPath('status', 'ready')
            ->assertJsonPath('profile_unlocked', true)
            ->assertJsonPath('data.nik', '1234567890123456');
    }

    public function test_full_flow_accepted_without_fields_fails_extraction(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-e2e-nofields']);
        $vision = Mockery::mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('check')->once()->andReturn(new OcrResponse([
            'requestId' => 'req-e2e-nofields', 'status' => 'PROCESSING',
        ], true, null));
        $vision->shouldReceive('status')->once()->andReturn(new OcrResponse([
            'status' => 'DONE',
            'decision' => 'ACCEPTED',
            'documentType' => 'KTP',
            'extractedFields' => [],
        ], true, null));
        $this->app->instance(IdentityDocumentAPIService::class, $vision);

        $this->withToken(JWTAuth::fromUser($user))
            ->postJson('/api/identity-documents', [
                'image' => UploadedFile::fake()->image('ktp.jpg'),
            ])->assertAccepted();
        $checkId = IdentityDocument::where('user_id', $user->id)->value('public_check_id');
        $record = IdentityDocument::where('public_check_id', $checkId)->first();
        $path = $record->file_path;
        Storage::put($path, Crypt::encrypt('image-content'));

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$checkId)
            ->assertOk()
            ->assertJsonPath('status', 'extraction_failed')
            ->assertJsonPath('profile_unlocked', false);

        Storage::assertMissing($path);
    }
}
