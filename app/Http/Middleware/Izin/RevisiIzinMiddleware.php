<?php

namespace App\Http\Middleware\Izin;

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RevisiIzinMiddleware
{

    protected PerizinanService $perizinanService;

    public function __construct(PerizinanService $perizinanService)
    {
        $this->perizinanService = $perizinanService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $izin_id = $request->route()->parameter('izin_id');
        $izin = $this->perizinanService->find($izin_id);
        $last_status = $izin->status_izin->last();
        if ($last_status->nama != StatusIzinEnum::REVISI_PERSYARATAN) {
            return redirect()->back();
        }
        return $next($request);
    }
    
}