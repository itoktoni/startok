<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->increments('pos_id');
            $table->string('pos_no');
            $table->integer('pos_total')->nullable();
            $table->integer('pos_payment')->nullable();
            $table->integer('pos_change')->nullable();
            $table->string('pos_payment_method')->nullable();
            $table->text('pos_keterangan')->nullable();
            $table->timestamp('pos_created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};
