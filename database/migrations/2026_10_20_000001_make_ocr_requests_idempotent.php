<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Idempotency OCR pipeline (plan 11 P1.5):
 * - request_id jadi nullable: baris dibuat persist-first (status pending) sebelum
 *   job di-dispatch, request_id diisi hanya ketika vision menerima request.
 * - (pemohon_id, syarat_izin_id, izin_id) = kunci idempotency; izin_id tersirat
 *   pemohon_id-nya, jadi sufisien unique (izin_id, syarat_izin_id) untuk menahan
 *   dobel-claim antar worker. Baris lama (syarat_izin_id NULL, mis. dari
 *   OcrAPIServiceImpl lama) tidak konflik karena MySQL unique index memperbolehkan
 *   kombinasi NULL.
 * - status: pending|processing|done|failed|failed_final (lihat App\Models\OcrRequest).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocr_requests', function (Blueprint $table) {
            $table->string('request_id', 50)->nullable()->change();
            $table->unsignedBigInteger('pemohon_id')->nullable();
            $table->unsignedBigInteger('syarat_izin_id')->nullable();
            $table->string('status', 20)->default('pending');
            $table->unique(['izin_id', 'syarat_izin_id']);
        });
    }

    public function down(): void
    {
        Schema::table('ocr_requests', function (Blueprint $table) {
            $table->dropUnique(['izin_id', 'syarat_izin_id']);
            $table->dropColumn(['pemohon_id', 'syarat_izin_id', 'status']);
            $table->string('request_id', 50)->nullable(false)->change();
        });
    }
};
