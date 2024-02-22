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
        Schema::create('catapult_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catapult_model_id')->onDelete('cascade');
            $table->string('relationship_method_name');
            $table->string('relationship_model');
            $table->string('relationship_method');
            $table->string('relationship');
            $table->string('foreign_key')->nullable();
            $table->string('model_foreign_key')->nullable();
            $table->string('related_model_foreign_key')->nullable();
            $table->string('table')->nullable();
            $table->string('local_key')->nullable();
            $table->string('owner_key')->nullable();
            $table->string('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catapult_relationships');
    }
};
