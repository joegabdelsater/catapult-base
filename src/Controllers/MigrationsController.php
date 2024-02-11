<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;
use Joegabdelsater\CatapultBase\Models\CatapultMigration;
use Joegabdelsater\CatapultBase\Builders\Migrations\MigrationBuilder;
use Joegabdelsater\CatapultBase\Builders\Migrations\ValidationBuilder;
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
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema; 
        use Joegabdelsater\CatapultBase\Classes\CatapultSchema;

    return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up()
            {
              return CatapultSchema::create('{$model->table_name}', function (Blueprint \$table) {
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

        $valid['created'] = false;

        if ($model->migration) {
            $model->migration()->update($valid);
        } else {
            $model->migration()->create($valid);
        }

        return redirect()->back();
    }

    public function destroy(CatapultMigration $migration)
    {
        CatapultMigration::destroy($migration->id);
        return redirect()->back();
    }

    public function generate(Model $model)
    {
        $model = Model::with('migration')->find($model->id);

        /** Create the temp migration */
        $migrationBuilder = new MigrationBuilder($model->migration);
        $migrationGenerator = new ClassGenerator(filePath: config('directories.temp_migrations'), fileName: 'create_' . $model->table_name . '_table.php', content: $migrationBuilder->build());
        $migrationGenerator->generate();

        /** Extract the validaiton rules from the temp migration and create the validation request */
        $validationBuilder = new ValidationBuilder(include($migrationGenerator->getFullPath()), $model->name);
        $validationContent = $validationBuilder->build();
        $requestGenerator = new ClassGenerator(filePath: config('directories.validation_requests'), fileName: $model->name . 'Request.php', content: $validationContent);
        $requestGenerator->generate();

        /** Modify the content of the temp migration */
        $migrationGenerator->modifyContent(stringModifications: [
            'use Joegabdelsater\CatapultBase\Classes\CatapultSchema;' => '',
            'return CatapultSchema' => 'Schema'
        ], regexModifications: [
            "/->validation\(['\"](.*?)['\"]\)/" => ''
        ])
            ->renameFile(date('Y_m_d') . '_' . time() . '_create_' . $model->table_name . '_table.php')
            ->moveFile(config('directories.migrations'));

        $model->migration->update(['created' => true]);
        return redirect()->route('catapult.migrations.index');
    }
}
