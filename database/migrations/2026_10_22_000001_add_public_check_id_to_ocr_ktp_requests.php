<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocr_ktp_requests', function (Blueprint $table) {
            $table->uuid('public_check_id')->nullable()->unique()->after('request_id');
            $table->string('status')->default('pending')->after('file_path');
            $table->json('result')->nullable()->after('status');
            $table->string('error')->nullable()->after('result');
        });
    }

    public function down(): void
    {
        Schema::table('ocr_ktp_requests', fn (Blueprint $table) => $table->dropColumn(['public_check_id', 'status', 'result', 'error']));
    }
};
