<?php

namespace App\Http\Middleware;

use App\Support\SignedFileUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards `GET /api/files/{file_name}` (route `api.render-file`) with a
 * cryptographic signature instead of jwt.auth, so stateless consumers
 * (the vision OCR service, the mobile app) can fetch a document without
 * credentials but only for a short window (see config/signed-files.php).
 *
 * On every invalid / missing / expired signature it responds 401 BEFORE
 * the request reaches the controller, i.e. before any disk or lookup.
 */
class VerifySignedFileUrl
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! SignedFileUrl::verify($request)) {
            return response()->json(['message' => 'Invalid or expired file URL signature.'], 401);
        }

        return $next($request);
    }
}
