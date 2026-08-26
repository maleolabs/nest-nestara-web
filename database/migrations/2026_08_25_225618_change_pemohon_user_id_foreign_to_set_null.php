<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mengubah FK pemohon.user_id menjadi ON DELETE SET NULL.
 *
 * Latar belakang: FK lama dibuat tanpa aksi onDelete (RESTRICT implisit),
 * sehingga akun user yang sudah memiliki data pemohon tidak bisa dihapus.
 * Dengan SET NULL, saat akun dihapus baris pemohon tetap hidup sebagai
 * "pemohon yatim" (user_id = NULL) sehingga seluruh riwayat izin yang
 * merujuk pemohon (izin.pemohon_id) ikut selamat.
 *
 * Kolom user_id memang sudah nullable sejak migration awal, jadi cukup
 * ganti constraint-nya saja. Nama FK dibuat eksplisit dan deterministik
 * ('pemohon_user_id_foreign', sama dengan konvensi Laravel dari migration
 * awal) agar dropForeign() di up()/down() selalu aman.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemohon', function (Blueprint $table) {
            $table->dropForeign('pemohon_user_id_foreign');
            $table->foreign('user_id', 'pemohon_user_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemohon', function (Blueprint $table) {
            $table->dropForeign('pemohon_user_id_foreign');
            // kembalikan ke kondisi awal: FK polos tanpa onDelete (RESTRICT implisit)
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }
};
