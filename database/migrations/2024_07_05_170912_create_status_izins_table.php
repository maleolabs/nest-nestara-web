<?php

use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
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
        Schema::create('status_izin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('izin_id');
            $table->enum('nama', StatusIzinEnum::all());
            $table->enum('progres', StatusIzinProgressEnum::all());
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->string('keterangan')->nullable(true);
            $table->timestamps();

            $table->foreign('izin_id')->references('id')->on('izin');
            $table->foreign('pegawai_id')->references('id')->on('pegawai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_izin');
    }
};
