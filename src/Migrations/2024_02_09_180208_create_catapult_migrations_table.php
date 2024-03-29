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
        Schema::create('catapult_migrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catapult_model_id')->onDelete('cascade');
            $table->text('migration_code')->nullable();
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
        Schema::dropIfExists('catapult_migrations');
    }
};
