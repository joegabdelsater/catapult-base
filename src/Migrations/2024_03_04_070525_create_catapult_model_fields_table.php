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
        Schema::create('catapult_model_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catapult_model_id')->constrained('catapult_models')->onDelete('cascade');
            $table->string('column_name');
            $table->string('column_type');
            $table->json('column_config')->nullable();
            $table->string('default')->nullable();
            $table->boolean('nullable')->default(false);
            $table->boolean('unique')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_fields');
    }
};
