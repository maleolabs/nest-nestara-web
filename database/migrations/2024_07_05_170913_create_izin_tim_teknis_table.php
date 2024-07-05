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
        Schema::create('izin_tim_teknis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('izin_id');
            $table->unsignedBigInteger('tim_teknis_id');
            $table->timestamps();

            $table->foreign('izin_id')->references('id')->on('izin');
            $table->foreign('tim_teknis_id')->references('id')->on('tim_teknis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_tim_teknis');
    }
};
