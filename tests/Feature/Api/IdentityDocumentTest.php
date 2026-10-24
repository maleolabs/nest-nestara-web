<?php

namespace Tests\Feature\Api;

use App\Models\OcrKtpRequest;
use App\Models\User;
use App\Services\APIClient\dto\OcrResponse;
use App\Services\APIClient\OcrAPIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        Storage::put($record->file_path, 'encrypted');
        $vision = Mockery::mock(OcrAPIService::class);
        $vision->shouldReceive('checkOcrRequestStatus')->once()->andReturn(new OcrResponse(['status' => $upstream], true, null));
        if (in_array($expected, ['done', 'manual_review'], true)) {
            $vision->shouldReceive('getOcrResultByRequestId')->once()->andReturn(new OcrResponse(['extractedTexts' => ['_document_type' => 'KTP', 'nama' => 'BUDI']], true, null));
        }
        $this->app->instance(OcrAPIService::class, $vision);

        $this->withToken(JWTAuth::fromUser($user))->getJson('/api/identity-documents/'.$record->public_check_id)
            ->assertOk()->assertJsonPath('status', $expected)
            ->assertJsonPath('profile_unlocked', $expected === 'done');
        $this->assertDatabaseHas('ocr_ktp_requests', ['id' => $record->id, 'status' => $expected, 'file_path' => null]);
        Storage::assertMissing('private_files/test.enc');
    }

    public static function terminalStatuses(): array
    {
        return [['DONE', 'done'], ['MANUAL_REVIEW', 'manual_review'], ['FAILURE', 'failed']];
    }
}
