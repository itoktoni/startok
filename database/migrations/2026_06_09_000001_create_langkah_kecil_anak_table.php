<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langkah_kecil_anak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('nama');
            $table->integer('umur')->nullable();
            $table->string('emoji')->default('👶');
            $table->string('avatar')->nullable();
            $table->json('skills')->nullable();
            $table->json('history')->nullable();
            $table->json('completed_skills')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_kecil_anak');
    }
};
