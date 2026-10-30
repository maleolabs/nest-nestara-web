<?php

namespace Tests\Feature;

use App\Models\OcrKtpRequest;
use App\Models\User;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\IdentityDocumentAPIService;
use App\Services\APIClient\Impl\IdentityDocumentAPIServiceImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IdentityDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_identity_document_api_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(IdentityDocumentAPIServiceImpl::class, $this->app->make(IdentityDocumentAPIService::class));
    }

    public function test_identity_check_sends_document_type_as_multipart_field(): void
    {
        Http::fake(['http://vision.test/*' => Http::response(['requestId' => 'req-sim', 'documentType' => 'SIM'])]);
        set_pengaturan(OCR_BASE_URL, 'http://vision.test', OCR_API_SETT_GROUP);
        set_pengaturan(OCR_API_KEY, 'test-key', OCR_API_SETT_GROUP);

        $response = app(IdentityDocumentAPIService::class)->check('image-content', 'sim.jpg', 'SIM', ['nik', 'nama']);

        $this->assertTrue($response->isSuccess());
        Http::assertSent(function ($request): bool {
            return $request->url() === 'http://vision.test/identity-document-checks'
                && $request->method() === 'POST'
                && str_contains($request->body(), 'name="documentType"')
                && str_contains($request->body(), 'SIM')
                && str_contains($request->body(), 'name="requiredKeys[]"');
        });
    }

    public function test_identity_check_defaults_document_type_to_ktp(): void
    {
        Http::fake(['http://vision.test/*' => Http::response(['requestId' => 'req-ktp', 'documentType' => 'KTP'])]);
        set_pengaturan(OCR_BASE_URL, 'http://vision.test', OCR_API_SETT_GROUP);
        set_pengaturan(OCR_API_KEY, 'test-key', OCR_API_SETT_GROUP);

        $response = app(IdentityDocumentAPIService::class)->check('image-content', 'ktp.jpg');

        $this->assertTrue($response->isSuccess());
        Http::assertSent(fn ($request): bool => $request->method() === 'POST'
            && str_contains($request->body(), 'name="documentType"')
            && str_contains($request->body(), 'KTP'));
    }

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
        $vision->shouldReceive('check')->once()->andReturn(new OcrResponse(['requestId' => 'req-1'], true, null));

        $response = $this->postJson('/api/identity-documents', ['image' => UploadedFile::fake()->image('ktp.jpg')]);

        $response->assertAccepted()->assertJsonPath('status', 'verifying');
        $this->assertDatabaseHas('ocr_ktp_requests', ['request_id' => 'req-1', 'status' => 'verifying']);
    }

    public function test_status_maps_accepted_result(): void
    {
        Storage::fake('local');
        $owner = $this->apiUser();
        $record = OcrKtpRequest::create(['user_id' => $owner->id, 'request_id' => 'req-2', 'public_check_id' => '11111111-1111-4111-8111-111111111111', 'file_path' => 'private_files/ktp', 'status' => 'pending']);
        Storage::put($record->file_path, Crypt::encrypt('image-content'));
        $vision = $this->mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('status')->andReturn(new OcrResponse([
            'status' => 'ACCEPTED',
            'extractedTexts' => ['nama' => 'Budi', 'secret' => 'nope'],
        ], true, null));

        $response = $this->getJson('/api/identity-documents/'.$record->public_check_id);
        $response->assertOk()->assertJsonPath('status', 'ready')->assertJsonPath('data.nama', 'Budi');
        Storage::assertMissing($record->file_path);
    }

    #[DataProvider('terminalVisionResponses')]
    public function test_terminal_vision_response_without_fields_is_not_502(array $payload, string $status): void
    {
        $user = $this->apiUser();
        $record = OcrKtpRequest::create([
            'user_id' => $user->id, 'request_id' => 'req-'.$status,
            'public_check_id' => '33333333-3333-4333-8333-333333333333', 'status' => 'pending',
            'file_path' => $status === 'accepted' ? 'private_files/terminal.enc' : null,
        ]);
        if ($status === 'accepted') {
            Storage::put('private_files/terminal.enc', Crypt::encrypt('image-content'));
        }
        $vision = $this->mock(IdentityDocumentAPIService::class);
        $vision->shouldReceive('status')->once()->andReturn(new OcrResponse($payload, true, null));

        $response = $this->getJson('/api/identity-documents/'.$record->public_check_id);

        $response->assertOk()->assertJsonPath('status', $status === 'accepted' ? 'extraction_failed' : $status)->assertJsonPath('data', []);
    }

    public static function terminalVisionResponses(): array
    {
        return [
            [['status' => 'ACCEPTED'], 'accepted'],
            [['status' => 'UNSUPPORTED_DOCUMENT'], 'rejected'],
            [['status' => 'MANUAL_REVIEW'], 'manual_review'],
            [['status' => 'FAILED', 'reasonCode' => 'OCR_FAILURE'], 'failed'],
        ];
    }

    public function test_status_hides_record_from_other_user(): void
    {
        $owner = User::factory()->create(['username' => fake()->unique()->userName()]);
        OcrKtpRequest::create(['user_id' => $owner->id, 'request_id' => 'req-3', 'public_check_id' => '22222222-2222-4222-8222-222222222222', 'status' => 'pending']);
        $this->apiUser();

        $this->getJson('/api/identity-documents/22222222-2222-4222-8222-222222222222')->assertNotFound();
    }
}
