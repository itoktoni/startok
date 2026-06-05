<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_detail_variant_id')->after('pos_detail_extra_price')->nullable();
            $table->dropColumn('pos_detail_variant');
        });
    }

    public function down(): void
    {
        Schema::table('pos_order_items', function (Blueprint $table) {
            $table->string('pos_detail_variant')->after('pos_detail_extra_price')->nullable();
            $table->dropColumn('pos_detail_variant_id');
        });
    }
};
