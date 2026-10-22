<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permit_letter_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('izin_id')->constrained('izin')->cascadeOnDelete();
            $table->string('status');
            $table->string('path')->nullable();
            $table->string('error')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('processing_at')->nullable();
            $table->timestamps();
            $table->index(['izin_id', 'status']);
        });
    }

    public function down(): void { Schema::dropIfExists('permit_letter_generations'); }
};
