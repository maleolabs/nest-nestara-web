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
        Schema::create('entri_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_perizinan_id');
            $table->string('nama', 100);
            $table->string('tipe', 20);
            $table->boolean('mandatori')->default(true);
            $table->integer('max');
            $table->boolean('is_multi_value')->default(false);
            $table->string('value_separator', 5)->default(';');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('jenis_perizinan_id')->references('id')->on('jenis_perizinan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entri_data');
    }
};
