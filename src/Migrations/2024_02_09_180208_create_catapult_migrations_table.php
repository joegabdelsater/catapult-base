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
            $table->foreignId('model_id')->onDelete('cascade');
            $table->string('column');
            $table->string('type');
            $table->boolean('nullable')->default(false);
            $table->boolean('unique')->default(false);
            $table->string('default')->nullable();
            $table->string('on_delete')->nullable();
            $table->string('related_table')->nullable();
            $table->string('related_column')->nullable();
            $table->string('enum_options')->nullable();
            $table->string('validation')->nullable();
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
