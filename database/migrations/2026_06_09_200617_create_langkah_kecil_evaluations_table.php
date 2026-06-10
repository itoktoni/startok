<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langkah_kecil_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('langkah_kecil_anak')->cascadeOnDelete();
            $table->string('skill_key');
            $table->string('skill_title')->nullable();
            $table->string('pilar')->nullable();
            $table->integer('points')->default(0);
            $table->integer('max_points')->default(10);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['anak_id', 'skill_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_kecil_evaluations');
    }
};
