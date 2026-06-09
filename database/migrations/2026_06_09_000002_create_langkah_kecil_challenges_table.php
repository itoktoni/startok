<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langkah_kecil_challenges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anak_id')->index();
            $table->string('category');
            $table->string('title');
            $table->string('emoji')->nullable();
            $table->integer('points')->default(0);
            $table->string('status')->default('pending');
            $table->date('date')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_kecil_challenges');
    }
};
