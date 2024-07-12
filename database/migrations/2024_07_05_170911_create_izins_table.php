<?php

use App\utils\enums\JenisPermohonanEnum;
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
            $table->unsignedBigInteger('penandatangan_id')->nullable(true);
            $table->integer('no_pendaftaran');
            $table->enum('jenis_permohonan', JenisPermohonanEnum::all())->default(JenisPermohonanEnum::BARU);
            $table->dateTime('tanggal_peninjauan')->nullable();
            $table->string('lokasi_izin')->nullable();
            $table->string('nomor_surat', 25)->nullable();
            $table->boolean('ambil_entri_lama')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('jenis_perizinan_id')->references('id')->on('jenis_perizinan');
            $table->foreign('pemohon_id')->references('id')->on('pemohon');
            $table->foreign('penandatangan_id')->references('id')->on('pegawai');
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
