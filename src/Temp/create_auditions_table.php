<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // replace with CatapultSchema then get it back to this before creating the migration

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         CatapultSchema::create('auditions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->validation('required');
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


class CatapultSchema {
    public static function create($tableName, $callback) {
        $table = new Blueprint($tableName);
        $callback($table);

        $validationRules = [];

        foreach($table->getColumns() as $column) {
            if ($column->validation) {
                $validationRules[$column->name] = $column->validation;
               $column->offsetUnset('validation');
            }
        }

       return $validationRules;
    }
}
