<?php

namespace Tests\Unit\Support;

use App\Models\OcrRequest;
use PHPUnit\Framework\TestCase;

/**
 * State-machine murni OcrRequest (idempotency OCR, plan P1.5) —
 * TANPA DB: model dibuat via forceFill, tidak pernah di-save.
 */
class OcrRequestStateTest extends TestCase
{
    private function makeRow(string $status, ?string $requestId = null): OcrRequest
    {
        return (new OcrRequest)->forceFill([
            'izin_id' => 1,
            'syarat_izin_id' => 2,
            'pemohon_id' => 3,
            'status' => $status,
            'request_id' => $requestId,
        ]);
    }

    public function test_pending_row_is_claimable(): void
    {
        $this->assertFalse($this->makeRow(OcrRequest::STATUS_PENDING)->isBlockedForResend());
    }

    public function test_processing_row_without_request_id_is_claimable(): void
    {
        // attempt sebelumnya mati sebelum vision menerima → boleh di-claim ulang
        $this->assertFalse($this->makeRow(OcrRequest::STATUS_PROCESSING, null)->isBlockedForResend());
    }

    public function test_processing_row_with_request_id_is_blocked(): void
    {
        // vision sudah menerima request → jangan kirim dobel
        $this->assertTrue($this->makeRow(OcrRequest::STATUS_PROCESSING, 'req-1')->isBlockedForResend());
    }

    public function test_done_row_is_blocked(): void
    {
        $this->assertTrue($this->makeRow(OcrRequest::STATUS_DONE, 'req-1')->isBlockedForResend());
    }

    public function test_failed_final_is_terminal(): void
    {
        $this->assertTrue($this->makeRow(OcrRequest::STATUS_FAILED_FINAL, 'req-1')->isBlockedForResend());
    }

    public function test_failed_is_retryable(): void
    {
        // attempt ini gagal di send, worker masih punya sisa percobaan → boleh kirim ulang
        $this->assertFalse($this->makeRow(OcrRequest::STATUS_FAILED, null)->isBlockedForResend());
    }

    public function test_status_constants_are_stable_strings(): void
    {
        // dipakai oleh kolom ocr_requests.status string(20)
        foreach ([
            OcrRequest::STATUS_PENDING,
            OcrRequest::STATUS_PROCESSING,
            OcrRequest::STATUS_DONE,
            OcrRequest::STATUS_FAILED,
            OcrRequest::STATUS_FAILED_FINAL,
        ] as $status) {
            $this->assertLessThanOrEqual(20, strlen($status));
        }
    }
}
