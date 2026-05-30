<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id', true);
            $table->string('discount_code')->unique();
            $table->string('discount_nama');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 12, 0);
            $table->decimal('discount_min_transaction', 12, 0)->default(0);
            $table->decimal('discount_max_amount', 12, 0)->nullable();
            $table->boolean('discount_active')->default(true);
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
