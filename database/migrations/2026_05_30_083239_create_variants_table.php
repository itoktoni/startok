<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id', true);
            $table->unsignedInteger('product_id');
            $table->string('variant_nama');
            $table->decimal('variant_harga', 12, 0);
            $table->text('variant_deskripsi')->nullable();
            $table->boolean('variant_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
            $table->unique(['product_id', 'variant_nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
