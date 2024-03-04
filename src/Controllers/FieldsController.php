<?php

namespace Joeabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Joeabdelsater\CatapultBase\Models\CatapultField;

use Illuminate\Support\Str;
use Joeabdelsater\CatapultBase\Classes\ModelService;
use Joeabdelsater\CatapultBase\Models\CatapultPackage;
use Joeabdelsater\CatapultBase\Builders\Migrations\MigrationBuilder;
use Joeabdelsater\CatapultBase\Builders\Migrations\ValidationBuilder;
use Joeabdelsater\CatapultBase\Builders\ClassGenerator;
use Illuminate\Support\Facades\Artisan;

class FieldsController extends BaseController
{

    public function create($modelId)
    {
        $model = CatapultModel::with('fields')->find($modelId);
        $models = CatapultModel::all();
        return view('catapult::fields.create', compact('model', 'models'));
    }

    public function store(Request $request, $modelId)
    {
        $request->merge(['catapult_model_id' => $modelId]);

        CatapultField::create($request->all());

        return redirect()->route('catapult.fields.create', ['modelId' => $modelId]);
    }

    public function build($modelId)
    {
        $model = CatapultModel::with('fields')->find($modelId);
        $migrationLines = [];
        $_validationLines = [];
        $validationLines = [];
        $filamentColumnLines = [];
        $filamentFieldLines = [];
        $imports = [];

        foreach ($model->fields as $field) {
            $migrationLines[] = $this->getMigrationLine($field);

            if ($field->validation) {
                $_validationLines[$field->column_name] = $field->validation;
            }

            if ($field->admin_column_type) {
                $result = $this->getAdminColumnLine($field);
                $filamentColumnLines[] = $result['line'];

                $imports = array_merge($imports, $result['import']);
            }


            if ($field->admin_field_type) {
                $result = $this->getAdminFieldLine($field);
                $filamentFieldLines[] = $result['line'];
                $imports = array_merge($imports, $result['import']);
            }
        }

        $imports = array_unique($imports);



        foreach ($_validationLines as $column => $validation) {
            $validationLines[] = "'" . $column . "' => '" . $validation . "'";
        }

        $validationCode = implode(",\n\t\t\t\t", $validationLines);
        $filamentColumnCode =  "\t" . implode(",\n\t\t\t\t", $filamentColumnLines);
        $filamentFieldCode =  "\t" . implode(",\n\t\t\t\t", $filamentFieldLines);
        $migrationCode =   implode("\n\t\t\t\t", $migrationLines);
        $importsCode = implode("\n", $imports);



        $this->generateMigration($model, $migrationCode);
        $this->generateValidation($model, $validationCode);
        $this->generateFilament($model, $filamentColumnCode, $filamentFieldCode, $importsCode);
    }

    public function getAdminFieldLine($field)
    {
        $imports = [];

        switch ($field->admin_field_type) {
            case 'text_input':
                $imports['import'] = ['use Filament\Forms\Components\TextInput;'];
                $imports['line'] = 'TextInput::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['readonly'])) {
                    $imports['line'] = $imports['line'] . '->readonly()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['numeric'])) {
                    $imports['line'] = $imports['line'] . '->numeric()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['password'])) {
                    $imports['line'] = $imports['line'] . '->password()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['disabled'])) {
                    $imports['line'] = $imports['line'] . '->disabled()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['placeholder'])) {
                    $imports['line'] = $imports['line'] . '->placeholder(\'' . $field->admin_field_config['placeholder'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['label'])) {
                    $imports['line'] = $imports['line'] . '->label(\'' . $field->admin_field_config['label'] . '\')';
                }
                if ($field->admin_field_config && isset($field->admin_field_config['min'])) {
                    $imports['line'] = $imports['line'] . '->minLength(\'' . $field->admin_field_config['min'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['max'])) {
                    $imports['line'] = $imports['line'] . '->maxLength(\'' . $field->admin_field_config['max'] . '\')';
                }

                break;

            case 'select':
                $imports['import'] = ['use Filament\Forms\Components\Select;'];
                $imports['line'] = 'Select::make(\'' . $field->column_name . '\')->options(' . $this->generateStaticOptions($field->admin_field_config['options']) . ')' . $this->generateSelectMethods($field->admin_field_config);
                break;

            case 'relationship_select':
                $imports['import'] = [
                    'use Filament\Forms\Components\Select;',
                    'use App\Models\\' . $field->admin_field_config['related_model'] . ';'
                ];

                $imports['line'] = 'Select::make(\'' . $field->column_name . '\')->options(' . $this->generateModelOptions($field->admin_field_config) . ')' . $this->generateSelectMethods($field->admin_field_config);
                break;

            case 'textarea':
                $imports['import'] = ['use Filament\Forms\Components\Textarea;'];
                $imports['line'] = 'Textarea::make(\'' . $field->column_name . '\')';
                break;


            case 'checkbox':
                $imports['import'] = ['use Filament\Forms\Components\Checkbox;'];
                $imports['line'] = 'Checkbox::make(\'' . $field->column_name . '\')';
                break;

            case 'toggle':
                $imports['import'] = ['use Filament\Forms\Components\Toggle;'];
                $imports['line'] = 'Toggle::make(\'' . $field->column_name . '\')';
                break;

            case 'radio':
                $imports['import'] = ['use Filament\Forms\Components\Radio;'];
                $imports['line'] = 'Radio::make(\'' . $field->column_name . '\')->options(' . $this->generateStaticOptions($field->admin_field_config['options']) . ')';
                break;

            case 'rich_editor':
                $imports['import'] = ['use Filament\Forms\Components\RichEditor;'];
                $imports['line'] = 'RichEditor::make(\'' . $field->column_name . '\')';
                break;


            case 'markdown_editor':
                $imports['import'] = ['use Filament\Forms\Components\MarkdownEditor;'];
                $imports['line'] = 'MarkdownEditor::make(\'' . $field->column_name . '\')';
                break;

            case 'file_upload':
                $imports['import'] = ['use Filament\Forms\Components\FileUpload;'];
                $imports['line'] = 'FileUpload::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['disk'])) {
                    $imports['line'] = $imports['line'] . '->disk(\'' . $field->admin_field_config['disk'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['directory'])) {
                    $imports['line'] = $imports['line'] . '->directory(\'' . $field->admin_field_config['directory'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['accepted_file_types'])) {
                    $imports['line'] = $imports['line'] . '->acceptedFileTypes(\'' . $field->admin_field_config['accepted_file_types'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['max_size'])) {
                    $imports['line'] = $imports['line'] . '->maxFileSize(\'' . $field->admin_field_config['max_size'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['min_size'])) {
                    $imports['line'] = $imports['line'] . '->minFileSize(\'' . $field->admin_field_config['min_size'] . '\')';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['multiple'])) {
                    $imports['line'] = $imports['line'] . '->multiple()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['image'])) {
                    $imports['line'] = $imports['line'] . '->image()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['avatar'])) {
                    $imports['line'] = $imports['line'] . '->avatar()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['imageEditor'])) {
                    $imports['line'] = $imports['line'] . '->imageEditor()';
                }

                if ($field->admin_field_config && isset($field->admin_field_config['openable'])) {
                    $imports['line'] = $imports['line'] . '->openable()';
                }
                break;

            case 'date_time':
                $imports['import'] = ['use Filament\Forms\Components\DateTimePicker;'];
                $imports['line'] = 'DateTimePicker::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['format'])) {
                    $imports['line'] = $imports['line'] . '->format(\'' . $field->admin_field_config['format'] . '\')';
                }

                break;

            case 'date':
                $imports['import'] = ['use Filament\Forms\Components\DatePicker;'];
                $imports['line'] =  'DatePicker::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['format'])) {
                    $imports['line'] = $imports['line'] . '->format(\'' . $field->admin_field_config['format'] . '\')';
                }
                break;

            case 'time':
                $imports['import'] = ['use Filament\Forms\Components\TimePicker;'];
                $imports['line'] = 'TimePicker::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['format'])) {
                    $imports['line'] = $imports['line'] . '->format(\'' . $field->admin_field_config['format'] . '\')';
                }
                break;
        }


        return $imports;
    }

    public function getAdminColumnLine($field)
    {
        $imports = [];

        switch ($field->admin_column_type) {
            case 'text_column':
                $imports['import'] = ['use Filament\Tables\Columns\TextColumn;'];
                $imports['line'] = 'TextColumn::make(\'' . $field->column_name . '\')';
                break;

            case 'relationship_text_column':
                $imports['import'] = ['use Filament\Tables\Columns\TextColumn;'];
                $imports['line'] = 'TextColumn::make(\'' . $field->admin_column_config['related_attribute'] . '\')';
                break;

            case 'image_column':
                $imports['import'] = ['use Filament\Tables\Columns\ImageColumn;'];
                $imports['line'] = 'ImageColumn::make(\'' . $field->column_name . '\')';

                if ($field->admin_column_config && isset($field->admin_column_config['size'])) {
                    $imports['line'] = $imports['line'] . '->size(' . $field->admin_column_config['size'] . ')';
                }

                if ($field->admin_column_config && isset($field->admin_column_config['disk'])) {
                    $imports['line'] = $imports['line'] . '->disk(' . $field->admin_column_config['disk'] . ')';
                }
                break;

            case 'color_column':
                $imports['import'] = ['use Filament\Tables\Columns\ColorColumn;'];
                $imports['line'] = 'ColorColumn::make(\'' . $field->column_name . '\')';
                break;

            case 'toggle_column':
                $imports['import'] = ['use Filament\Tables\Columns\ToggleColumn;'];
                $imports['line'] = 'ToggleColumn::make(\'' . $field->column_name . '\')';
                break;

            case 'checkbox_column':
                $imports['import'] = ['use Filament\Tables\Columns\CheckboxColumn;'];
                $imports['line'] = 'CheckboxColumn::make(\'' . $field->column_name . '\')';
                break;
        }


        return $imports;
    }

    public function getMigrationLine($field): string
    {
        $migrationLine = '';

        switch ($field->column_type) {
            case 'string':
                $field->column_config && isset($field->column_config['length']) ?
                    $migrationLine = '$table->string(\'' . $field->column_name . '\', ' . $field->column_config['length'] . ')'
                    : $migrationLine = '$table->string(\'' . $field->column_name . '\')';
                break;

            case 'text':
                $field->column_config && isset($field->column_config['length']) ? $migrationLine = '$table->text(\'' . $field->column_name . '\', ' . $field->column_config['length'] . ')' : $migrationLine = '$table->text(\'' . $field->column_name . '\')';
                break;

            case 'integer':
                $migrationLine = '$table->integer(\'' . $field->column_name . '\')';
                break;

            case 'bigInteger':
                $migrationLine = '$table->bigInteger(\'' . $field->column_name . '\')';
                break;

            case 'boolean':
                $migrationLine = '$table->boolean(\'' . $field->column_name . '\')';
                break;

            case 'date':
                $migrationLine = '$table->date(\'' . $field->column_name . '\')';
                break;

            case 'dateTime':
                $migrationLine = '$table->dateTime(\'' . $field->column_name . '\')';
                break;

            case "decimal":
                $scale = $field->column_config && isset($field->column_config['scale']) ? $field->column_config['scale'] : 2;
                $precision = $field->column_config && isset($field->column_config['precision']) ? $field->column_config['precision'] : 8;

                $migrationLine = '$table->decimal(\'' . $field->column_name . '\', ' . $precision . ', ' . $scale . ')';
                break;

            case "double":
                $scale = $field->column_config && isset($field->column_config['scale']) ? $field->column_config['scale'] : 2;
                $precision = $field->column_config && isset($field->column_config['precision']) ? $field->column_config['precision'] : 8;

                $migrationLine = '$table->double(\'' . $field->column_name . '\', ' . $precision . ', ' . $scale . ')';

                break;

            case "float":
                $scale = $field->column_config && isset($field->column_config['scale']) ? $field->column_config['scale'] : 2;
                $precision = $field->column_config && isset($field->column_config['precision']) ? $field->column_config['precision'] : 8;

                $migrationLine = '$table->float(\'' . $field->column_name . '\', ' . $precision . ', ' . $scale . ')';
                break;

            case "json":
                $migrationLine = '$table->json(\'' . $field->column_name . '\')';
                break;

            case "relationship":
                $migrationLine = '$table->foreignId(\'' . $field->column_name . '\')';
                break;

            case "enum":
                $migrationLine = '$table->enum(\'' . $field->column_name . '\', ' . $field->column_config['enum_options'] . ')';
                break;
        }


        if ($field->nullable) {
            $migrationLine .= '->nullable()';
        }

        if ($field->unique) {
            $migrationLine .= '->unique()';
        }

        if ($field->default) {
            $migrationLine .= '->default(\'' . $field->column_config->default . '\')';
        }

        if ($field->column_type === 'relationship') {
            if ($field->column_config && isset($field->column_config['on_delete'])) {
                $migrationLine = $migrationLine . '->constrained()->onDelete(\'' . $field->column_config['on_delete'] . '\')';
            }
        }


        return $migrationLine . ';';
    }

    public function generateStaticOptions($optionsString)
    {

        $options = explode(',', $optionsString);

        $options = array_map(function ($option) {
            $kv = explode(':', $option);
            return "'" . $kv[0] . "'" . ' => ' . "'" . $kv[1] . "'";
        }, $options);

        return '[' . implode('\n,', $options) . ']';
    }

    public function generateModelOptions($config)
    {
        return $config['related_model'] . "::all()->pluck('" . $config['pluck'] . "')";
    }

    public function generateSelectMethods($config)
    {
        $methods = "";

        if (isset($config['searchable'])) {
            $methods .= "->searchable()";
        }

        if (isset($config['multiple'])) {
            $methods .= "->multiple()";
        }

        return $methods;
    }

    public function generateMigration($model, $migrationCode)
    {
        if (!is_dir(config('directories.temp_migrations'))) {
            mkdir(config('directories.temp_migrations'));
        }

        if (!is_dir(config('directories.validation_requests'))) {
            mkdir(config('directories.validation_requests'));
        }

        $stub =
            "<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up()
        {
          return Schema::create('{$model->table_name}', function (Blueprint \$table) {
                \$table->id();
                $migrationCode
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


        $migrationGenerator = new ClassGenerator(filePath: config('directories.temp_migrations'), fileName: 'create_' . $model->table_name . '_table.php', content: $stub);
        $migrationGenerator->generate()
            ->renameFile(date('Y_m_d') . '_' . time() . '_create_' . $model->table_name . '_table.php')
            ->moveMigration(config('directories.migrations'));
    }


    public function generateValidation($model, $validationCode)
    {
        $stub = <<<PHP
            <?php
            namespace App\Http\Requests;

            use Illuminate\Foundation\Http\FormRequest;

            class {$model->name}Request extends FormRequest
            {
                /**
                 * Determine if the user is authorized to make this request.
                 */
                public function authorize(): bool
                {
                    return auth('web')->check();
                }

                /**
                 * Get the validation rules that apply to the request.
                 *
                 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
                 */
                public function rules(): array
                {
                    return [
                        $validationCode
                    ];
                }
            }
        PHP;

        $validationGenerator = new ClassGenerator(filePath: config('directories.validation_requests'), fileName: $model->name . 'Request.php', content: $stub);
        $validationGenerator->generate();
    }

    public function generateFilament($model, $columnsCode, $fieldsCode, $importsCode)
    {   

        Artisan::call('make:filament-resource', [
            'name' => $model->name,
        ]);

        $resourcePath = config('directories.filament_resources') . '/' . $model->name . 'Resource.php';

        $resource = file_get_contents($resourcePath);

        //handle the importsCode
        $pattern = '/(use [^;]+;)$/m';
        $replacement = "$1\n$importsCode";
        $modifiedContents = preg_replace($pattern, $replacement, $resource, 1);

        //handle the fields
        $pattern = '/->schema\(\[\s*\/\/.*?\]\);/s';
        $replacement = '->schema([
            ' . $fieldsCode . '
        ]);';
        $modifiedContents = preg_replace($pattern, $replacement, $modifiedContents);

        //handle the columns
        $pattern = '/->columns\(\[\s*\/\/.*?\]\);/s';
        $replacement = '->columns([
            ' . $columnsCode . '
        ]);';
        $modifiedContents = preg_replace($pattern, $replacement, $modifiedContents);
        file_put_contents($resourcePath, $modifiedContents);
    }
}
