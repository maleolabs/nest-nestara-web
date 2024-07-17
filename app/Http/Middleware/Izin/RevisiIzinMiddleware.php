<?php

namespace App\Http\Middleware\Izin;

use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\StatusIzinEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RevisiIzinMiddleware
{

    protected PerizinanService $perizinanService;

    protected EncryptionService $encryptionService;

    public function __construct(
        PerizinanService $perizinanService,
        EncryptionService $encryptionService
    ) {
        $this->perizinanService = $perizinanService;
        $this->encryptionService = $encryptionService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $izin_id = $request->route('izin_id');
        $izin_id = $this->encryptionService->decryptId($izin_id);
        $izin = $this->perizinanService->find($izin_id);
        $last_status = $izin->status_izin->last();
        if ($last_status->nama != StatusIzinEnum::REVISI_PERSYARATAN) {
            return redirect()->back();
        }
        return $next($request);
    }
    
}