<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_nama');
            $table->integer('product_harga')->nullable();
            $table->text('product_keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
