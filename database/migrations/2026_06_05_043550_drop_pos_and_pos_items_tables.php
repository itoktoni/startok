<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pos_items');
        Schema::dropIfExists('pos');
    }

    public function down(): void
    {
        // Recreate pos table
        Schema::create('pos', function ($table) {
            $table->increments('pos_id');
            $table->string('pos_no');
            $table->decimal('pos_total', 12);
            $table->decimal('pos_payment', 12);
            $table->decimal('pos_change', 12);
            $table->string('pos_payment_method');
            $table->text('pos_keterangan')->nullable();
            $table->timestamp('pos_created_at')->nullable();
        });

        // Recreate pos_items table
        Schema::create('pos_items', function ($table) {
            $table->increments('pos_items_id');
            $table->integer('pos_id');
            $table->integer('product_id');
            $table->string('product_nama');
            $table->decimal('product_harga', 12);
            $table->integer('pos_qty');
            $table->decimal('pos_item_total', 12);
            $table->text('pos_item_note')->nullable();
        });
    }
};
