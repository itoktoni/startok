<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langkah_kecil_completed_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('langkah_kecil_anak')->cascadeOnDelete();
            $table->string('key');
            $table->string('emoji')->nullable();
            $table->string('title');
            $table->string('pilar')->nullable();
            $table->string('color')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['anak_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_kecil_completed_skills');
    }
};
