<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langkah_kecil_skill_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained('langkah_kecil_skills')->cascadeOnDelete();
            $table->string('title');
            $table->string('emoji')->nullable();
            $table->string('feature')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_kecil_skill_activities');
    }
};
