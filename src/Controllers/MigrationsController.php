<?php

namespace Joeabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Joeabdelsater\CatapultBase\Models\CatapultMigration;
use Joeabdelsater\CatapultBase\Builders\Migrations\MigrationBuilder;
use Joeabdelsater\CatapultBase\Builders\Migrations\ValidationBuilder;
use Joeabdelsater\CatapultBase\Builders\ClassGenerator;
use Illuminate\Support\Str;

class MigrationsController extends BaseController
{

    public function index()
    {
        $models = CatapultModel::with('migration', 'relationships')->get();

        $models = $models->map(function ($model) {
            $warning = false;

            $belongsToRelationships = $model->relationships->filter(function ($relationship) {
                return $relationship->relationship_method === 'belongsTo';
            });

            if (count($belongsToRelationships) > 0) {
                $belongsTo = $belongsToRelationships->filter(function ($relationship) {
                    $model = CatapultModel::where('name', str_replace('::class', '', $relationship->relationship_model))->first();
                    if ($model->migration) {
                        return !$model->migration->created;
                    } else {
                        return true;
                    }
                });


                if ($belongsTo->count() > 0) {
                    $warnings = $belongsTo->pluck('relationship_model');
                    $warnings = $warnings->map(function ($warning) {
                        return str_replace('::class', '.php', $warning);
                    });
                    $warning = 'Make sure to generate the ' . implode(' & ', $warnings->toArray()) . ' migration(s) first!';
                }
            }

            $model->warning_message = $warning;
            return $model;
        });

        // dd($models->toArray());
        return view('catapult::migrations.index', compact('models'));
    }


    public function create(CatapultModel $model)
    {
        $model = CatapultModel::with(['migration', 'relationships'])->find($model->id);

        //Suggest the migration foreign code based on the relationship
        // if we're in create mode
        $foreignKeys = [];
        $foreignKeysCode = '';


        foreach ($model->relationships as $relationship) {
            $foreignModelName = $relationship->foreign_key;

            if ($relationship->relationship_method  === 'belongsTo') {
                if (!$foreignModelName) {
                    $foreignModelName = str_replace('::class', '', $relationship->relationship_model);
                    $foreignModelName = strtolower(Str::snake(Str::singular($foreignModelName))) . '_id';
                }

                $foreignKeys[] = "\$table->foreignId('$foreignModelName')->onDelete('cascade');";
            }
        }

        $foreignKeysCode = implode("\n\t\t\t\t\t", $foreignKeys);

        $base =
            "<?php
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;
        use Joeabdelsater\CatapultBase\Classes\CatapultSchema;

    return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up()
            {
              return CatapultSchema::create('{$model->table_name}', function (Blueprint \$table) {
                    \$table->id();
                    $foreignKeysCode
                    \$table->timestamps();
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('{$model->table_name}');
            }
        };



        ";


        if ($model->migration) {
            $base = $model->migration->migration_code;
        }

        $availableColumnTypes = config('migrations.column_types');

        return view('catapult::migrations.create', compact('model', 'availableColumnTypes', 'base'));
    }

    public function store(Request $request, CatapultModel $model)
    {

        $valid = $request->validate([
            'migration_code' => 'required',
            'validation' => 'nullable',
        ]);

        if ($model->migration) {
            $valid['updated'] = true;

            $model->migration()->update($valid);
        } else {
            $model->migration()->create($valid);
        }

        return redirect()->route('catapult.migrations.index');
    }

    public function destroy(CatapultMigration $migration)
    {
        CatapultMigration::destroy($migration->id);
        return redirect()->back();
    }

    public function generate(CatapultModel $model)
    {
        $model = CatapultModel::with('migration')->find($model->id);

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
            'use Joeabdelsater\CatapultBase\Classes\CatapultSchema;' => '',
            'return CatapultSchema' => 'Schema'
        ], regexModifications: [
            "/->validation\(['\"](.*?)['\"]\)/" => ''
        ])
            ->renameFile(date('Y_m_d') . '_' . time() . '_create_' . $model->table_name . '_table.php')
            ->moveMigration(config('directories.migrations'));

        $model->migration->update(['created' => true, 'updated' => false]);
        return redirect()->route('catapult.migrations.index');
    }
}
