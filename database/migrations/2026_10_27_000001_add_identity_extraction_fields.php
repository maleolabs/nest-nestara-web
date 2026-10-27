<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocr_ktp_requests', function (Blueprint $table): void {
            $table->string('extraction_request_id')->nullable()->after('request_id');
        });
    }

    public function down(): void
    {
        Schema::table('ocr_ktp_requests', fn (Blueprint $table) => $table->dropColumn('extraction_request_id'));
    }
};
