<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('langkah_kecil_anak', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('nama');
            $table->integer('tanggal_lahir')->nullable()->after('umur');
            $table->integer('bulan_lahir')->nullable()->after('tanggal_lahir');
            $table->integer('tahun_lahir')->nullable()->after('bulan_lahir');
        });
    }

    public function down(): void
    {
        Schema::table('langkah_kecil_anak', function (Blueprint $table) {
            $table->dropColumn(['gender', 'tanggal_lahir', 'bulan_lahir', 'tahun_lahir']);
        });
    }
};
