<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_items', function (Blueprint $table) {
            $table->increments('pos_items_id');
            $table->integer('pos_id');
            $table->integer('product_id');
            $table->string('product_nama');
            $table->integer('product_harga');
            $table->integer('pos_qty');
            $table->integer('pos_item_total')->nullable();
            $table->text('pos_item_note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_items');
    }
};
