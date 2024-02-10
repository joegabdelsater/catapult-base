<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;
use Joegabdelsater\CatapultBase\Models\CatapultMigration;
use Joegabdelsater\CatapultBase\Builders\Migrations\MigrationBuilder;
use Joegabdelsater\CatapultBase\Builders\ClassGenerator;
class MigrationsController extends BaseController
{

    public function index()
    {
        $models = Model::with('migration')->get();
        return view('catapult::migrations.index', compact('models'));
    }

    public function create(Model $model)
    {
        $base =
            "<?php
       namespace Joegabdelsater\CatapultBase\Temp;
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
                Schema::create('{$model->table_name}', function (Blueprint \$table) {
                    \$table->id();
                    
                    \$table->timestamps();
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
        
        

        ";

        $model = Model::with(['migration', 'relationships'])->find($model->id);

        if ($model->migration) {
            $base = $model->migration->migration_code;
        }

        $availableColumnTypes = config('migrations.column_types');

        return view('catapult::migrations.create', compact('model', 'availableColumnTypes', 'base'));
    }

    public function store(Request $request, Model $model)
    {

        $valid = $request->validate([
            'migration_code' => 'required',
            'validation' => 'nullable',
        ]);

        if($model->migration){
            $model->migration()->update($valid);
        }else{
            $model->migration()->create($valid);
        }

        return redirect()->back();
    }

    public function destroy(CatapultMigration $migration)
    {
        CatapultMigration::destroy($migration->id);
        return redirect()->back();
    }

    public function generate(Model $model) {
        $model = Model::with('migration')->find($model->id);
        $migrationBuilder = new MigrationBuilder($model->migration);
        ClassGenerator::generate(fileName: 'create_' . $model->table_name . '_table.php', content: $migrationBuilder->build(), contentType: 'temp_migrations');

        return redirect()->route('catapult.migrations.index');
    }
}
