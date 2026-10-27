<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permit_ready_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('izin_id')->constrained('izin')->cascadeOnDelete();
            $table->string('event', 80);
            $table->timestamps();
            $table->unique(['izin_id', 'event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permit_ready_notifications');
    }
};
