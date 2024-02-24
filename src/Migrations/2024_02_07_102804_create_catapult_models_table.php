<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catapult_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('table_name');
            $table->boolean('only_guard_id')->default(false);
            $table->json('packages')->nullable();
            $table->string('extends')->nullable();
            $table->json('implements')->nullable();
            $table->json('traits')->nullable();
            $table->json('properties')->nullable();
            $table->json('imports')->nullable();
            $table->boolean('created')->default(false);
            $table->boolean('updated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catapult_models');
    }
};
