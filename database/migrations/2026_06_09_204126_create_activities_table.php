<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // storytelling, bermain_peran, permainan, monolog, etc.
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('desc')->nullable();
            $table->string('image')->nullable();
            $table->string('moral')->nullable();
            $table->json('ages')->nullable();
            $table->json('skills')->nullable();
            $table->json('data')->nullable(); // flexible content (pages, roles, questions, steps, etc.)
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['type', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
