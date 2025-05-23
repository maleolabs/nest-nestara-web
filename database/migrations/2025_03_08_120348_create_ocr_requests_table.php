<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ocr_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('izin_id')->nullable(false);
            $table->string('request_id', 50)->nullable(false);
            $table->timestamps();

            $table->foreign('izin_id')->references('id')->on('izin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocr_requests');
    }
};
