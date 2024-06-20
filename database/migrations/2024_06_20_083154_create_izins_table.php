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
        Schema::create('izin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_perizinan_id');
            $table->unsignedBigInteger('pemohon_id');

            $table->foreign('jenis_perizinan_id')->references('id')->on('jenis_perizinan');
            $table->foreign('pemohon_id')->references('id')->on('pemohon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
