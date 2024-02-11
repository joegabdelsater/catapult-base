<?php

namespace Joegabdelsater\CatapultBase\Builders\Migrations;

use Joegabdelsater\CatapultBase\Interfaces\Builder;
use Joegabdelsater\CatapultBase\Models\CatapultMigration;

class ValidationBuilder implements Builder
{
    public $migrationClass;
    public $modelName;

    public function __construct($migrationClass, $modelName)
    {
        $this->migrationClass = $migrationClass;
        $this->modelName = $modelName;
    }




    public function build(): string
    {
        $rules = $this->migrationClass->up();
        $validationRules = [];

        foreach($rules as $key => $value) {
            $validationRules[] = "'$key' => '$value'";
        }

        $validationRules = implode(",\n", $validationRules);

        return <<<PHP
            <?php
            namespace App\Http\Requests;

            use Illuminate\Foundation\Http\FormRequest;
            
            class {$this->modelName}Request extends FormRequest
            {
                /**
                 * Determine if the user is authorized to make this request.
                 */
                public function authorize(): bool
                {
                    return false;
                }
            
                /**
                 * Get the validation rules that apply to the request.
                 *
                 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
                 */
                public function rules(): array
                {
                    return [
                        $validationRules
                    ];
                }
            }
            

            PHP;
    }
}
