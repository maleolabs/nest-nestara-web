<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index untuk optimasi query dashboard/list izin:
     * - lookup status terakhir per izin (MAX(created_at) GROUP BY izin_id)
     * - filter by nama/progres status
     * - filter rentang tanggal & sorting created_at
     */
    public function up(): void
    {
        Schema::table('status_izin', function (Blueprint $table) {
            $table->index(['izin_id', 'nama', 'created_at'], 'status_izin_izin_nama_created_idx');
            $table->index(['nama', 'progres'], 'status_izin_nama_progres_idx');
        });

        Schema::table('izin', function (Blueprint $table) {
            $table->index('created_at', 'izin_created_at_idx');
            $table->index('no_pendaftaran', 'izin_no_pendaftaran_idx');
        });

        Schema::table('pemohon', function (Blueprint $table) {
            $table->index('nama_pemohon', 'pemohon_nama_idx');
        });
    }

    public function down(): void
    {
        Schema::table('status_izin', function (Blueprint $table) {
            $table->dropIndex('status_izin_izin_nama_created_idx');
            $table->dropIndex('status_izin_nama_progres_idx');
        });

        Schema::table('izin', function (Blueprint $table) {
            $table->dropIndex('izin_created_at_idx');
            $table->dropIndex('izin_no_pendaftaran_idx');
        });

        Schema::table('pemohon', function (Blueprint $table) {
            $table->dropIndex('pemohon_nama_idx');
        });
    }
};
