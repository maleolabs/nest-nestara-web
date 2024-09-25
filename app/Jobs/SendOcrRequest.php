<?php

namespace App\Jobs;

use App\Models\Izin;
use App\Services\DomainServices\OcrService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOcrRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Izin $izin;

    /**
     * Create a new job instance.
     */
    public function __construct(Izin $izin)
    {
        $this->izin = $izin;
    }

    /**
     * Execute the job.
     */
    public function handle(OcrService $ocrService): void
    {
        try {
            $response = $ocrService->doOcrRequestForNewDataRegistation($this->izin);
            Log::channel('app')->info('OCR request processed succcessfully', $response);
        } catch (Exception $e) {
            Log::channel('app')->info('OCR request failed in job', ['message' => $e->getMessage()]);
        }
    }
}
