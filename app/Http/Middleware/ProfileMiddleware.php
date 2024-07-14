<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $current_route = Route::currentRouteName();
        $auth_user = auth()->user();
        $auth_user_role = $auth_user->getRoleNames()[0];
        switch ($auth_user_role) {
            case 'admin':
                return $current_route == 'profile.admin' ? $next($request) : redirect()->route('profile.admin');
            case 'pengelola_layanan':
                return $current_route == 'profile.pengelola_layanan' ? $next($request) : redirect()->route('profile.pengelola_layanan');
            case 'pegawai':
                return $current_route == 'profile.pegawai' ? $next($request) : redirect()->route('profile.pegawai');
            case 'pemohon':
                return $current_route == 'profile.pemohon' ? $next($request) : redirect()->route('profile.pemohon');
            default:
                abort(403);
        }
        return $next($request);
    }
}
