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
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->onDelete('cascade');
            $table->string('relationship_method_name');
            $table->string('foreign_key')->nullable();
            $table->string('local_key')->nullable();
            $table->string('owner_key')->nullable();
            $table->string('model');
            $table->string('relationship_model');
            $table->string('relationship_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
