<?php

namespace App\Http\Middleware\Izin;

use App\Services\AppService\EncryptionService;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowIzinMiddleware
{

    protected EncryptionService $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $izin_id = $request->route()->parameter('izin_id');
        try {
            $izin_id = $this->encryptionService->decryptId($izin_id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $auth_user = auth()->user();
        $user_pemohon_izin = $auth_user->pemohon ? $auth_user->pemohon->izin->pluck('id'): [];
        if (!$izin_id || in_array($izin_id, $user_pemohon_izin->all()))
            return $next($request);
        abort(403);
    }
    
}