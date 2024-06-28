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
        Schema::create('dokumen_pemohon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemohon_id');
            $table->unsignedBigInteger('syarat_izin_id');
            $table->string('nama_file');

            $table->foreign('pemohon_id')->references('id')->on('pemohon');
            $table->foreign('syarat_izin_id')->references('id')->on('syarat_izin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pemohon');
    }
};
