<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('langkah_kecil_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anak_id')->index();
            $table->string('label');
            $table->string('time')->nullable();
            $table->boolean('done')->default(false);
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_kecil_schedules');
    }
};
