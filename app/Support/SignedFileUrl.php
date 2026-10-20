<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RuntimeException;

/**
 * Cryptographic signing for URLs served by `GET /api/files/{file_name}`
 * (route `api.render-file`).
 *
 * URL scheme produced by make():
 *
 *     {APP_URL}/api/files/{base64filename}?exp={unixtime}&sig={hex}
 *
 * Query parameters:
 * - `exp` : expiry time, unix timestamp (seconds).
 * - `sig` : HMAC-SHA256 (64 chars hex) over "{base64filename}:{exp}".
 *
 * The signing key never leaves the server: it is derived from APP_KEY
 * (fallback: JWT_SECRET) with hash_hkdf() using a domain-specific info
 * string, so a leaked signature cannot be replayed for other purposes.
 * Signing material (key, computed signature) must never be logged.
 */
class SignedFileUrl
{
    private const HKDF_INFO = 'signed-file-url';

    private const SIGNATURE_LENGTH = 64; // hex chars of a SHA-256 HMAC

    /**
     * Build a signed, short-lived URL for a private file, e.g. for
     * API responses handed to the mobile app or the OCR (vision) service.
     */
    public static function make(string $fileName, ?int $ttl = null): string
    {
        $encoded = base64_encode($fileName);
        $exp = Carbon::now()->getTimestamp() + ($ttl ?? self::defaultTtl());
        $sig = self::signature($encoded, $exp);

        return route('api.render-file', [
            'file_name' => $encoded,
            'exp' => $exp,
            'sig' => $sig,
        ]);
    }

    /**
     * Validate a signed file URL. Verifies, in order:
     * the route has a `file_name`, query contains exactly `exp` and `sig`,
     * `exp` is a well-formed future timestamp, and the HMAC matches the
     * request's file_name + exp using a constant-time comparison.
     */
    public static function verify(Request $request): bool
    {
        $file_name = $request->route('file_name');
        if (! is_string($file_name) || $file_name === '') {
            return false;
        }

        $query = $request->query();
        $extra_keys = array_diff(array_keys($query), ['exp', 'sig']);
        $missing_keys = array_diff(['exp', 'sig'], array_keys($query));
        if ($extra_keys !== [] || $missing_keys !== []) {
            return false;
        }

        $exp = $query['exp'];
        $sig = $query['sig'];
        if (! is_string($exp) || ! ctype_digit($exp)) {
            return false;
        }
        if (! is_string($sig) || strlen($sig) !== self::SIGNATURE_LENGTH || ! ctype_xdigit($sig)) {
            return false;
        }

        $exp = (int) $exp;
        if ($exp <= Carbon::now()->getTimestamp()) {
            return false;
        }

        return hash_equals(self::signature($file_name, $exp), $sig);
    }

    private static function signature(string $encodedFileName, int $exp): string
    {
        return hash_hmac('sha256', $encodedFileName.':'.$exp, self::key());
    }

    private static function key(): string
    {
        $app_key = (string) config('app.key');
        if ($app_key !== '') {
            if (str_starts_with($app_key, 'base64:')) {
                $decoded = base64_decode(substr($app_key, strlen('base64:')), true);
                if ($decoded !== false && $decoded !== '') {
                    $app_key = $decoded;
                }
            }

            return hash_hkdf('sha256', $app_key, 32, self::HKDF_INFO);
        }

        $jwt_secret = (string) config('jwt.secret');
        if ($jwt_secret !== '') {
            return hash('sha256', $jwt_secret);
        }

        throw new RuntimeException('APP_KEY / JWT_SECRET is not configured; cannot sign file URLs.');
    }

    private static function defaultTtl(): int
    {
        return (int) config('signed-files.ttl', 900);
    }
}
