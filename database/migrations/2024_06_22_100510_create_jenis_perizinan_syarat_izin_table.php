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
        Schema::create('jenis_perizinan_syarat_izin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_perizinan_id');
            $table->unsignedBigInteger('syarat_izin_id');

            $table->foreign('jenis_perizinan_id')->references('id')->on('jenis_perizinan');
            $table->foreign('syarat_izin_id')->references('id')->on('syarat_izin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_perizinan_syarat_izin');
    }
};
