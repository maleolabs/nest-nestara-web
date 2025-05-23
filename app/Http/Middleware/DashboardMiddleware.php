<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth_role = auth()->user()->getRoleNames()[0];
        switch ($auth_role) {
            case ADMIN_ROLE:
                return redirect()->route('admin.dashboard');
            case FRONT_OFFICE_ROLE:
                return redirect()->route('layanan.dashboard');
            case BACK_OFFICE_ROLE:
                return redirect()->route('pegawai.dashboard');
            default:
                abort(403, "You don't have permission to access this url");
        }
        return $next($request);
    }
}
