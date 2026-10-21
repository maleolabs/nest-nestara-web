<?php

namespace Tests\Feature\Api;

use App\Support\SignedFileUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Feature tests for the signed-url guard on GET /api/files/{file_name}
 * (route api.render-file, wrapped by VerifySignedFileUrl middleware).
 *
 * NO DB: files are served from a faked local storage disk, signature is
 * validated by middleware before the controller touches anything.
 * (MySQL is not available in the sandbox by design; this suite avoids it.)
 */
class SignedFileUrlEndpointTest extends TestCase
{
    private const FILE_NAME = 'pengesahan.pdf';

    private const FILE_BODY = '%PDF-1.4 nestara signed-url fixture';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::disk('local')->put(
            'private_files/dokumen_persyaratan_izin/'.self::FILE_NAME,
            Crypt::encrypt(self::FILE_BODY)
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_valid_signature_returns_200_with_file_content(): void
    {
        $url = SignedFileUrl::make(self::FILE_NAME, 300);

        $response = $this->get($url);

        $response->assertStatus(200);
        $this->assertSame(self::FILE_BODY, $response->getContent());
    }

    public function test_missing_signature_returns_401(): void
    {
        $plain = base64_encode(self::FILE_NAME);

        $this->get("/api/files/{$plain}")->assertStatus(401);
    }

    public function test_tampered_signature_returns_401(): void
    {
        $url = SignedFileUrl::make(self::FILE_NAME, 300);
        preg_match('/&sig=([0-9a-f]{64})$/', $url, $m);
        $tampered_sig = ($m[1][0] === 'a') ? 'b'.substr($m[1], 1) : 'a'.substr($m[1], 1);

        $tampered_url = preg_replace('/&sig=[0-9a-f]{64}$/', '&sig='.$tampered_sig, $url);

        $this->get($tampered_url)->assertStatus(401);
    }

    public function test_tampered_exp_returns_401(): void
    {
        $url = SignedFileUrl::make(self::FILE_NAME, 300);
        preg_match('/\?exp=(\d+)/', $url, $m);

        $tampered_url = str_replace('?exp='.$m[1], '?exp='.($m[1] + 999_999), $url);

        $this->get($tampered_url)->assertStatus(401);
    }

    public function test_expired_signature_returns_401(): void
    {
        Carbon::setTestNow('2026-01-01 10:00:00');
        $url = SignedFileUrl::make(self::FILE_NAME, 60);

        Carbon::setTestNow('2026-01-01 10:01:05');
        $this->get($url)->assertStatus(401);
    }

    public function test_unknown_query_parameter_returns_401(): void
    {
        $url = SignedFileUrl::make(self::FILE_NAME, 300).'&aaa=1';

        $this->get($url)->assertStatus(401);
    }

    public function test_response_is_private_and_not_stored(): void
    {
        $response = $this->get(SignedFileUrl::make(self::FILE_NAME, 300));

        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_signature_for_wrong_file_name_returns_401(): void
    {
        // sig sah untuk FILE_NAME, dipakai untuk file berbeda
        $url = SignedFileUrl::make(self::FILE_NAME, 300);
        $other_b64 = base64_encode('lain-lain.pdf');
        preg_match('/\?(exp=[0-9]+&sig=[0-9a-f]{64})$/', $url, $m);

        $this->get("/api/files/{$other_b64}?{$m[1]}")->assertStatus(401);
    }
}
