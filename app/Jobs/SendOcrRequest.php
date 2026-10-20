<?php

namespace App\Jobs;

use App\Models\Izin;
use App\Models\OcrRequest;
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
            Log::channel('app')->info('OCR request processed succcessfully', is_array($response) ? $response : []);
        } catch (Exception $e) {
            Log::channel('app')->info('OCR request failed in job', ['message' => $e->getMessage()]);
            // rethrow agar worker menjalankan retry (--tries=3); idempotency dijaga
            // oleh status baris OcrRequest (syarat done/processing tidak dikirim ulang).
            throw $e;
        }
    }

    /**
     * Kegagalan final (--tries habis): tandai baris OCR yang belum selesai
     * sebagai terminal failed_final supaya kegagalan terlihat di DB.
     */
    public function failed(\Throwable $e): void
    {
        OcrRequest::where('izin_id', $this->izin->id)
            ->whereNotIn('status', [OcrRequest::STATUS_DONE, OcrRequest::STATUS_FAILED_FINAL])
            ->update(['status' => OcrRequest::STATUS_FAILED_FINAL]);

        Log::channel('app')->info('OCR request failed finally', [
            'izin_id' => $this->izin->id,
            'message' => $e->getMessage(),
        ]);
    }
}
