<?php

namespace Tests\Unit\Support;

use App\Support\SignedFileUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Unit tests for the SignedFileUrl builder/verifier.
 *
 * App-only (Tests\TestCase), no DB: the verifier works purely on the
 * request object and config().
 */
class SignedFileUrlTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_make_builds_url_with_exp_and_sig_and_it_verifies(): void
    {
        $url = SignedFileUrl::make('dokumen_persyaratan_izin/ktp-abc.pdf', 300);

        $this->assertStringStartsWith(url('/api/files/'), $url);
        $this->assertStringContainsString('?exp=', $url);
        $this->assertMatchesRegularExpression('/&sig=[0-9a-f]{64}$/', $url);

        $this->assertTrue(SignedFileUrl::verify($this->requestFor($url)));
    }

    public function test_url_is_valid_within_ttl_and_fails_right_after_expiry(): void
    {
        Carbon::setTestNow('2026-01-01 10:00:00');
        $url = SignedFileUrl::make('ktp-abc.pdf', 60);

        $this->assertTrue(SignedFileUrl::verify($this->requestFor($url)));

        Carbon::setTestNow('2026-01-01 10:01:01');
        $this->assertFalse(SignedFileUrl::verify($this->requestFor($url)));
    }

    public function test_tampered_sig_fails(): void
    {
        $url = SignedFileUrl::make('ktp-abc.pdf', 300);

        preg_match('/&sig=([0-9a-f]{64})$/', $url, $m);
        $tampered_sig = ($m[1][0] === 'a') ? 'b'.substr($m[1], 1) : 'a'.substr($m[1], 1);
        $tampered_url = preg_replace('/&sig=[0-9a-f]{64}$/', '&sig='.$tampered_sig, $url);

        $this->assertFalse(SignedFileUrl::verify($this->requestFor($tampered_url)));
    }

    public function test_tampered_exp_fails(): void
    {
        $url = SignedFileUrl::make('ktp-abc.pdf', 300);

        // geser exp lebih jauh: sig yang lama tidak lagi cocok
        preg_match('/\?exp=(\d+)/', $url, $m);
        $tampered_url = str_replace('?exp='.$m[1], '?exp='.($m[1] + 999_999), $url);

        $this->assertFalse(SignedFileUrl::verify($this->requestFor($tampered_url)));
    }

    public function test_unknown_query_param_fails(): void
    {
        $url = SignedFileUrl::make('ktp-abc.pdf', 300).'&debug=1';

        $this->assertFalse(SignedFileUrl::verify($this->requestFor($url)));
    }

    public function test_missing_sig_or_exp_fails(): void
    {
        $url = SignedFileUrl::make('ktp-abc.pdf', 300);
        $this->assertFalse(SignedFileUrl::verify($this->requestFor(url('/api/files/'.rawurlencode(base64_encode('ktp-abc.pdf'))))));
    }

    public function test_default_ttl_comes_from_config(): void
    {
        $this->assertSame(900, (int) config('signed-files.ttl'));
    }

    /**
     * Build a Request carrying the proper `api.render-file` route bound,
     * because SignedFileUrl::verify() reads file_name from the route.
     */
    private function requestFor(string $url): Request
    {
        $request = Request::create($url);
        $request->setRouteResolver(function () use ($request) {
            return $this->app['router']->getRoutes()->getByName('api.render-file')->bind($request);
        });

        return $request;
    }
}
