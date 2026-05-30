<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_id', true);
            $table->string('pos_order_code')->unique();
            $table->enum('pos_payment_method', ['cash', 'qris', 'cod']);
            $table->decimal('pos_subtotal', 12, 0);
            $table->decimal('pos_discount', 12, 0)->default(0);
            $table->decimal('pos_tax', 12, 0)->default(0);
            $table->decimal('pos_shipping_cost', 12, 0)->default(0);
            $table->decimal('pos_total', 12, 0);
            $table->enum('pos_shipping_type', ['cod_berbah', 'cod_piyungan', 'delivery'])->default('cod_berbah');
            $table->string('pos_shipping_address')->nullable();
            $table->decimal('pos_shipping_lat', 10, 7)->nullable();
            $table->decimal('pos_shipping_lng', 10, 7)->nullable();
            $table->string('pos_voucher_code')->nullable();
            $table->decimal('pos_voucher_discount', 12, 0)->default(0);
            $table->enum('pos_status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->text('pos_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_orders');
    }
};
