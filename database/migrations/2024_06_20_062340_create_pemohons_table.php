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
        Schema::create('pemohon', function (Blueprint $table) {
            $table->id();
            $table->string('sumber_identitas', 10);
            $table->string('nomor_id', 15);
            $table->string('nama_pemohon', 100);
            $table->string('no_telepon', 15);
            $table->bigInteger('provinsi');
            $table->bigInteger('kabupaten');
            $table->bigInteger('kecamatan');
            $table->bigInteger('kelurahan');
            $table->string('detail_alamat', 255);
            $table->string('alamat_luar_negeri', 255)->nullable(true);

            $table->unique('nomor_id', 'nomor_id_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemohon');
    }
};
