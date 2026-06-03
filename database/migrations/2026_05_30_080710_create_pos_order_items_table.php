<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_detail_id', true);
            $table->unsignedBigInteger('pos_order_id');
            $table->string('pos_detail_product_name');
            $table->decimal('pos_detail_unit_price', 12, 0);
            $table->integer('pos_detail_quantity');
            $table->decimal('pos_detail_extra_price', 12, 0)->default(0);
            $table->string('pos_detail_variant')->nullable();
            $table->string('pos_detail_note')->nullable();
            $table->decimal('pos_detail_line_total', 12, 0);
            $table->timestamps();

            $table->foreign('pos_order_id')->references('pos_id')->on('pos_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_order_items');
    }
};
