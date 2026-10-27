<?php

namespace Tests\Feature\Api;

use App\Models\OcrKtpRequest;
use App\Models\User;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\IdentityDocumentAPIService;
use App\Services\APIClient\OcrAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

final class IdentityDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_lookup_is_scoped_to_authenticated_client(): void
    {
        $owner = User::factory()->create(['username' => 'identity-owner']);
        $other = User::factory()->create(['username' => 'identity-other']);
        $record = OcrKtpRequest::create([
            'user_id' => $owner->id, 'request_id' => 'req-owner',
            'public_check_id' => '11111111-1111-4111-8111-111111111111', 'status' => 'pending',
        ]);

        $this->withToken(JWTAuth::fromUser($other))->getJson('/api/identity-documents/'.$record->public_check_id)->assertNotFound();
    }

    #[DataProvider('terminalStatuses')]
    public function test_worker_status_transition_cleans_file(string $upstream, string $expected): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-'.$expected]);
        $record = OcrKtpRequest::create([
            'user_id' => $user->id, 'request_id' => 'req-'.$expected,
            'public_check_id' => '22222222-2222-4222-8222-222222222222', 'status' => 'pending',
            'file_path' => 'private_files/test.enc',
        ]);
        Storage::put($record->file_path, Crypt::encrypt('image-content'));
        $identityVision = Mockery::mock(IdentityDocumentAPIService::class);
        $payload = ['status' => $upstream];
        if (in_array($expected, ['accepted', 'manual_review'], true)) {
            $payload['extractedTexts'] = ['_document_type' => 'KTP', 'nama' => 'BUDI'];
        }
        $identityVision->shouldReceive('status')->once()->with('req-'.$expected)
            ->andReturn(new OcrResponse($payload, true, null));
        $this->app->instance(IdentityDocumentAPIService::class, $identityVision);
        if ($expected === 'accepted') {
            $ocr = Mockery::mock(OcrAPIService::class);
            $ocr->shouldReceive('sendKtpImageRequest')->once()->withArgs(fn (): bool => true)->andReturn(new OcrResponse(['requestId' => 'ocr-'.$expected], true, null));
            $ocr->shouldReceive('checkOcrRequestStatus')->never();
            $this->app->instance(OcrAPIService::class, $ocr);
        }

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$record->public_check_id)
            ->assertOk()->assertJsonPath('status', $expected === 'accepted' ? 'extracting' : $expected)
            ->assertJsonPath('profile_unlocked', false);
        $this->assertDatabaseHas('ocr_ktp_requests', ['id' => $record->id, 'status' => $expected === 'accepted' ? 'extracting' : $expected]);
        if ($expected === 'accepted') {
            Storage::assertExists('private_files/test.enc');
        } else {
            Storage::assertMissing('private_files/test.enc');
        }
    }

    public function test_extraction_completion_returns_ready_and_cleans_file(): void
    {
        [$user, $record] = $this->extractionRecord('ready');
        $ocr = Mockery::mock(OcrAPIService::class);
        $ocr->shouldReceive('checkOcrRequestStatus')->once()->with('ocr-ready')
            ->andReturn(new OcrResponse(['status' => 'DONE'], true, null));
        $ocr->shouldReceive('getOcrResultByRequestId')->once()->with('ocr-ready')
            ->andReturn(new OcrResponse(['extractedTexts' => ['nama' => 'BUDI']], true, null));
        $this->app->instance(OcrAPIService::class, $ocr);

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$record->public_check_id)
            ->assertOk()->assertJsonPath('status', 'ready')->assertJsonPath('ready', true)
            ->assertJsonPath('profile_unlocked', true)
            ->assertJsonPath('data.nama', 'BUDI');
        Storage::assertMissing($record->file_path);
    }

    public function test_extraction_failure_returns_extraction_failed_and_cleans_file(): void
    {
        [$user, $record] = $this->extractionRecord('failed');
        $ocr = Mockery::mock(OcrAPIService::class);
        $ocr->shouldReceive('checkOcrRequestStatus')->once()->with('ocr-failed')
            ->andReturn(new OcrResponse(['status' => 'FAILURE'], true, null));
        $this->app->instance(OcrAPIService::class, $ocr);

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$record->public_check_id)
            ->assertOk()->assertJsonPath('status', 'extraction_failed')
            ->assertJsonPath('reason_code', 'ocr_processing_failed');
        Storage::assertMissing($record->file_path);
    }

    private function extractionRecord(string $suffix): array
    {
        Storage::fake('local');
        $user = User::factory()->create(['username' => 'identity-extraction-'.$suffix]);
        $path = 'private_files/extraction-'.$suffix.'.enc';
        $record = OcrKtpRequest::create([
            'user_id' => $user->id, 'request_id' => 'req-extraction-'.$suffix,
            'extraction_request_id' => 'ocr-'.$suffix,
            'public_check_id' => '55555555-5555-4555-8555-555555555555',
            'status' => 'extracting', 'file_path' => $path,
        ]);
        Storage::put($path, Crypt::encrypt('image-content'));

        return [$user, $record];
    }

    public function test_vision_failure_returns_persisted_terminal_state(): void
    {
        $user = User::factory()->create(['username' => 'identity-fallback']);
        $record = OcrKtpRequest::create([
            'user_id' => $user->id, 'request_id' => 'req-fallback',
            'public_check_id' => '33333333-3333-4333-8333-333333333333', 'status' => 'pending',
        ]);
        $identityVision = Mockery::mock(IdentityDocumentAPIService::class);
        $identityVision->shouldReceive('status')->once()->with('req-fallback')
            ->andReturnUsing(function () use ($record): OcrResponse {
                $record->update(['status' => 'done', 'result' => ['nama' => 'BUDI']]);

                return new OcrResponse([], false, 'upstream failure');
            });
        $this->app->instance(IdentityDocumentAPIService::class, $identityVision);

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$record->public_check_id)
            ->assertOk()->assertJsonPath('status', 'done')->assertJsonPath('data.nama', 'BUDI');
    }

    #[DataProvider('invalidVisionStatuses')]
    public function test_unknown_or_missing_vision_status_is_persisted_as_failed(?string $upstream): void
    {
        $user = User::factory()->create(['username' => 'identity-invalid-status-'.($upstream ?? 'missing')]);
        $record = OcrKtpRequest::create([
            'user_id' => $user->id, 'request_id' => 'req-invalid',
            'public_check_id' => '44444444-4444-4444-8444-444444444444', 'status' => 'pending',
        ]);
        $identityVision = Mockery::mock(IdentityDocumentAPIService::class);
        $identityVision->shouldReceive('status')->once()->with('req-invalid')
            ->andReturn(new OcrResponse($upstream === null ? [] : ['status' => $upstream], true, null));
        $this->app->instance(IdentityDocumentAPIService::class, $identityVision);

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$record->public_check_id)
            ->assertOk()->assertJsonPath('status', 'failed');
        $this->assertDatabaseHas('ocr_ktp_requests', ['id' => $record->id, 'status' => 'failed']);
    }

    public static function invalidVisionStatuses(): array
    {
        return [['UNKNOWN'], [null]];
    }

    public static function terminalStatuses(): array
    {
        return [
            ['ACCEPTED', 'accepted'],
            ['MANUAL_REVIEW', 'manual_review'],
            ['REJECTED', 'rejected'],
            ['FAILURE', 'failed'],
        ];
    }
}
