<?php

namespace Joeabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Joeabdelsater\CatapultBase\Models\CatapultField;
use Joeabdelsater\CatapultBase\Builders\ClassGenerator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class FieldsController extends BaseController
{

    public function create($modelId)
    {
        $model = CatapultModel::with('fields')->find($modelId);
        $models = CatapultModel::all();
        return view('catapult::fields.create', compact('model', 'models'));
    }

    public function delete($fieldId)
    {
        CatapultField::find($fieldId)->delete();
        return redirect()->back();
    }

    public function store(Request $request, $modelId)
    {


        $checkboxes = ['nullable', 'unique', 'translatable'];
        $jsCheckboxes = ['required', 'disabled', 'readonly', 'numeric', 'password', 'searchable', 'multiple', 'image', 'avatar', 'image_editor', 'openable'];

        $valid = $request->validate([
            'column_name' => 'required',
            'column_type' => 'required',
            'nullable' => 'nullable',
            'unique' => 'nullable',
            'default' => 'string|nullable',
            'validation' => 'string|nullable',
            'admin_column_type' => 'string|nullable',
            'admin_column_config' => 'array|nullable',
            'admin_field_type' => 'string|nullable',
            'admin_field_config' => 'array|nullable',
            'translatable' => 'nullable',
        ]);


        foreach ($checkboxes as $checkbox) {
            if (!$request->has($checkbox)) {
                $valid[$checkbox] = 0;
            } else {
                $valid[$checkbox] = 1;
            }
        }

        if (isset($valid['admin_column_config'])) {
            foreach ($jsCheckboxes as $checkbox) {
                if (!$request->has($checkbox)) {
                    $valid['admin_column_config'][$checkbox] = 0;
                } else {
                    $valid['admin_column_config'][$checkbox] = 1;
                }
            }
        }

        if (isset($valid['admin_column_field'])) {
            foreach ($jsCheckboxes as $checkbox) {
                if (!$request->has($checkbox)) {
                    $valid['admin_column_config'][$checkbox] = 0;
                } else {
                    $valid['admin_column_config'][$checkbox] = 1;
                }
            }
        }


        $valid['catapult_model_id'] = $modelId;

        CatapultField::create($valid);

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
        $filamentImports = [];
        $filamentTraits = [];

        foreach ($model->fields as $field) {
            $migrationLines[] = $this->getMigrationLine($field);

            if ($field->validation) {
                $_validationLines[$field->column_name] = $field->validation;
            }

            if ($field->admin_column_type) {
                $result = $this->getAdminColumnLine($field);
                $filamentColumnLines[] = $result['line'];

                $filamentImports = array_merge($filamentImports, $result['import']);
            }


            if ($field->admin_field_type) {
                $result = $this->getAdminFieldLine($field);
                $filamentFieldLines[] = $result['line'];
                $filamentImports = array_merge($filamentImports, $result['import']);
            }
        }

        $filamentImports = array_unique($filamentImports);

        if ($model->packages && in_array('filament_translatable', $model->packages)) {
            $filamentImports[] = 'use Filament\Resources\Concerns\Translatable;';
            $filamentTraits[] = 'use Translatable;';
        }

        foreach ($_validationLines as $column => $validation) {
            $validationLines[] = "'" . $column . "' => '" . $validation . "'";
        }

        $validationCode = implode(",\n\t\t\t\t", $validationLines);
        $filamentColumnCode =  "\t" . implode(",\n\t\t\t\t", $filamentColumnLines);
        $filamentFieldCode =  "\t" . implode(",\n\t\t\t\t", $filamentFieldLines);
        $migrationCode =   implode("\n\t\t\t\t", $migrationLines);
        $filamentImportsCode = implode("\n", $filamentImports);
        $filamentTraitsCode = implode("\n", $filamentTraits);



        $this->generateMigration($model, $migrationCode);
        $this->generateValidation($model, $validationCode);
        $this->generateFilament($model, $filamentColumnCode, $filamentFieldCode, $filamentImportsCode, $filamentTraitsCode);

        return redirect()->route('catapult.models.create');
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

                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }
                break;

            case 'relationship_select':
                $imports['import'] = [
                    'use Filament\Forms\Components\Select;',
                    'use App\Models\\' . $field->admin_field_config['related_model'] . ';'
                ];

                $imports['line'] = 'Select::make(\'' . $field->column_name . '\')->options(' . $this->generateModelOptions($field->admin_field_config) . ')' . $this->generateSelectMethods($field->admin_field_config);

                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }
                break;

            case 'textarea':
                $imports['import'] = ['use Filament\Forms\Components\Textarea;'];
                $imports['line'] = 'Textarea::make(\'' . $field->column_name . '\')';
                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }
                break;


            case 'checkbox':
                $imports['import'] = ['use Filament\Forms\Components\Checkbox;'];
                $imports['line'] = 'Checkbox::make(\'' . $field->column_name . '\')';
                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }
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

                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }
                break;


            case 'markdown_editor':
                $imports['import'] = ['use Filament\Forms\Components\MarkdownEditor;'];

                $imports['line'] = 'MarkdownEditor::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }
                break;

            case 'file_upload':
                $imports['import'] = ['use Filament\Forms\Components\FileUpload;'];
                $imports['line'] = 'FileUpload::make(\'' . $field->column_name . '\')';

                if ($field->admin_field_config && isset($field->admin_field_config['required'])) {
                    $imports['line'] = $imports['line'] . '->required()';
                }

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

    public function generateFilament($model, $columnsCode, $fieldsCode, $importsCode, $traitsCode)
    {
        Artisan::call('make:filament-resource', [
            'name' => $model->name,
        ]);

        $this->modifyResource($model, $importsCode, $fieldsCode, $columnsCode, $traitsCode);

        /** Modify the resource pages if translation is required */
        if ($model->packages && in_array('filament_translatable', $model->packages)) {
            $this->modifyResourcePages($model);
        }
    }

    public function modifyResource($model, $importsCode, $fieldsCode, $columnsCode, $traitsCode)
    {
        $resourcePath = config('directories.filament_resources') . '/' . $model->name . 'Resource.php';


        /** Modify the main resource file */
        $resource = file_get_contents($resourcePath);

        //handle the importsCode
        $pattern = '/(use [^;]+;)$/m';
        $replacement = "$1\n$importsCode";
        $modifiedContents = preg_replace($pattern, $replacement, $resource, 1);

        $positionForUse = strpos($modifiedContents, "{") + 1; // Find the first occurrence of '{' and add 1 to move after it
        $modifiedContents = substr_replace($modifiedContents, "\n    " . $traitsCode . "\n", $positionForUse, 0);

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

    public function modifyResourcePages($model)
    {
        $resourcePagesPath = config('directories.filament_resources') . '/' . $model->name . 'Resource/Pages';

        $this->modifyCreatePage($model, $resourcePagesPath);
        $this->modifyEditPage($model, $resourcePagesPath);
        $this->modifyListPage($model, $resourcePagesPath);
    }

    public function modifyCreatePage($model, $resourcePagesPath)
    {
        $createPagePath = $resourcePagesPath . '/Create' . $model->name . '.php';
        $resourcePageContent = file_get_contents($createPagePath);

        $useStatement = 'use CreateRecord\Concerns\Translatable;';
        $getHeaderActionsMethod = <<<METHOD
   
       protected function getHeaderActions(): array
       {
           return [
               Actions\LocaleSwitcher::make(),
               // ...
           ];
       }
   METHOD;

        // Locate the position to insert the `use` statement (after the opening class line)
        $positionForUse = strpos($resourcePageContent, "{") + 1; // Find the first occurrence of '{' and add 1 to move after it
        $resourcePageContent = substr_replace($resourcePageContent, "\n    " . $useStatement . "\n", $positionForUse, 0);

        // Assuming there's a closing brace for the class, add the new method before it
        // This is a simplified approach; you might need a more reliable way to find the insertion point
        $positionForMethod = strrpos($resourcePageContent, '}') - 1;
        $resourcePageContent = substr_replace($resourcePageContent, $getHeaderActionsMethod, $positionForMethod, 0);

        // Write the modified content back to the file
        file_put_contents($createPagePath, $resourcePageContent);
    }

    public function modifyEditPage($model, $resourcePagesPath)
    {
        $editPagePath = $resourcePagesPath . '/Edit' . $model->name . '.php';
        // Read the existing content of the file
        $resourcePageContent = file_get_contents($editPagePath);

        // Prepare the new lines to be added
        $useStatement = 'use EditRecord\Concerns\Translatable;';

        // Check if the use statement already exists to avoid duplication
        if (!str_contains($resourcePageContent, $useStatement)) {
            $positionForUse = strpos($resourcePageContent, "{") + 1;
            $resourcePagecontent = substr_replace($resourcePageContent, "\n    " . $useStatement . "\n", $positionForUse, 0);
        }

        // Locate the `getHeaderActions` method and insert the new action
        $pattern = '/(getHeaderActions\(\): array\s*{\s*return \[)([^]]+)/';
        $replacement = '$1$2    Actions\LocaleSwitcher::make(),';


        $resourcePageContent = preg_replace($pattern, $replacement, $resourcePageContent);

        file_put_contents($editPagePath, $resourcePageContent);
    }

    public function modifyListPage($model, $resourcePagesPath)
    {

        $listPagePath = $resourcePagesPath . '/List' . Str::plural($model->name) . '.php';



        // Read the existing content of the file
        $resourcePageContent = file_get_contents($listPagePath);

        // Prepare the new lines to be added

        $useStatement = 'use ListRecords\Concerns\Translatable;';


        // Check if the use statement already exists to avoid duplication
        if (!str_contains($resourcePageContent, $useStatement)) {
            $positionForUse = strpos($resourcePageContent, "{") + 1;
            $resourcePageContent = substr_replace($resourcePageContent, "\n    " . $useStatement . "\n", $positionForUse, 0);
        }

        // Locate the `getHeaderActions` method and insert the new action
        $pattern = '/(getHeaderActions\(\): array\s*{\s*return \[)([^]]+)/';
        $replacement = '$1$2    Actions\LocaleSwitcher::make(),';


        $resourcePageContent = preg_replace($pattern, $replacement, $resourcePageContent);

        file_put_contents($listPagePath, $resourcePageContent);
    }
}
