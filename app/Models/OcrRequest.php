<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * OcrRequest = record idempotency pipeline OCR (plan 11 P1.5).
 *
 * Baris dibuat persist-first (STATUS_PENDING) sebelum SendOcrRequest di-dispatch,
 * lalu diklaim ke STATUS_PROCESSING oleh worker sebelum request dikirim ke vision.
 * Kunci idempotency: (pemohon_id, syarat_izin_id, izin_id) — pemohon_id tersirat
 * dari izin, jadi unik di level DB adalah (izin_id, syarat_izin_id).
 */
class OcrRequest extends Model
{
    use HasFactory;

    protected $table = 'ocr_requests';

    protected $guarded = ['id'];

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_DONE = 'done';

    public const STATUS_FAILED = 'failed';

    public const STATUS_FAILED_FINAL = 'failed_final';

    /**
     * Baris yang tidak boleh mengirim request baru ke vision:
     * selesai (done), terminal fail, atau processing yang SUDAH diterima vision
     * (request_id terisi). processing tanpa request_id artinya attempt sebelumnya
     * mati sebelum vision menerima → boleh di-claim ulang.
     */
    public function isBlockedForResend(): bool
    {
        if ($this->status === self::STATUS_DONE) {
            return true;
        }

        if ($this->status === self::STATUS_FAILED_FINAL) {
            return true;
        }

        return $this->status === self::STATUS_PROCESSING && $this->request_id !== null;
    }
}
