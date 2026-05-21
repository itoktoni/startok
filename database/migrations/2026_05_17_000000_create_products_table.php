<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->primary('product_id');
            $table->string('product_nama');
            $table->decimal('product_harga', 10, 2)->nullable();
            $table->text('product_keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
