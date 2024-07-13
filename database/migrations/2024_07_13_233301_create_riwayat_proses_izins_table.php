<?php

use App\utils\enums\BackOfficeStatusEnum;
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
        Schema::create('riwayat_proses_izin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_izin_id');
            $table->enum('progres', StatusIzinProgressEnum::all());
            $table->enum('keterangan', BackOfficeStatusEnum::all())->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_proses_izin');
    }
};
