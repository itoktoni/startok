<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_detail_product_id')->after('pos_order_id')->nullable();
        });

        DB::statement('UPDATE pos_order_items SET pos_detail_product_id = NULL');

        Schema::table('pos_order_items', function (Blueprint $table) {
            $table->dropColumn('pos_detail_product_name');
        });
    }

    public function down(): void
    {
        Schema::table('pos_order_items', function (Blueprint $table) {
            $table->string('pos_detail_product_name')->after('pos_order_id')->nullable();
            $table->dropColumn('pos_detail_product_id');
        });
    }
};
