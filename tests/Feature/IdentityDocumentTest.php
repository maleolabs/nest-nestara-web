<?php

namespace Tests\Feature;

use App\Models\OcrKtpRequest;
use App\Models\User;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\IdentityDocumentAPIService;
use App\Services\APIClient\OcrAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IdentityDocumentTest extends TestCase
{
    use RefreshDatabase;

    private function apiUser(): User
    {
        $user = User::factory()->create(['username' => fake()->unique()->userName()]);
        $this->withoutMiddleware();
        auth('api')->setUser($user);

        return $user;
    }

    public function test_authenticated_upload_persists_owned_check(): void
    {
        Storage::fake('local');
        $this->apiUser();
        $vision = $this->mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('check')->once()->andReturn(new OcrResponse(['checkId' => 'req-1'], true, null));

        $response = $this->postJson('/api/identity-documents', ['image' => UploadedFile::fake()->image('ktp.jpg')]);

        $response->assertAccepted()->assertJsonPath('status', 'pending');
        $this->assertDatabaseHas('ocr_ktp_requests', ['request_id' => 'req-1', 'status' => 'pending']);
    }

    public function test_status_is_ownership_bound_and_maps_result(): void
    {
        Storage::fake('local');
        $owner = $this->apiUser();
        $record = OcrKtpRequest::create(['user_id' => $owner->id, 'request_id' => 'req-2', 'public_check_id' => '11111111-1111-4111-8111-111111111111', 'file_path' => 'private_files/ktp', 'status' => 'pending']);
        Storage::put($record->file_path, 'encrypted');
        $vision = $this->mock(OcrAPIService::class);
        $vision->shouldReceive('checkOcrRequestStatus')->andReturn(new OcrResponse(['status' => 'DONE'], true, null));
        $vision->shouldReceive('getOcrResultByRequestId')->andReturn(new OcrResponse(['extractedTexts' => ['nama' => 'Budi', 'secret' => 'nope']], true, null));

        $response = $this->getJson('/api/identity-documents/'.$record->public_check_id);
        $response->assertOk()->assertJsonPath('status', 'done')->assertJsonPath('data.nama', 'Budi')->assertJsonMissingPath('data.secret');
        Storage::assertMissing($record->file_path);
        $this->assertNull($record->refresh()->file_path);
    }

    public function test_status_hides_record_from_other_user(): void
    {
        $owner = User::factory()->create(['username' => fake()->unique()->userName()]);
        OcrKtpRequest::create(['user_id' => $owner->id, 'request_id' => 'req-3', 'public_check_id' => '22222222-2222-4222-8222-222222222222', 'status' => 'pending']);
        $this->apiUser();

        $this->getJson('/api/identity-documents/22222222-2222-4222-8222-222222222222')->assertNotFound();
    }
}
