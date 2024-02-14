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
        Schema::create('catapult_controller_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catapult_controller_id')->onDelete('cascade');
            $table->string('method');
            $table->string('controller_method');
            $table->string('route_name')->nullable();
            $table->string('route_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catapult_controller_routes');
    }
};
