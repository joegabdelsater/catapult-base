<?php

namespace Joegabdelsater\CatapultBase\Builders\Models;

use Illuminate\Database\Eloquent\Model;
use Joegabdelsater\CatapultBase\Builders\Models\RelationshipFactory;
use Joegabdelsater\CatapultBase\Builders\Models\HasOne;
use Joegabdelsater\CatapultBase\Builders\Models\HasMany;


class ModelBuilder
{
    public $model;
    public $relationshipFactory;
    public $relationships = [];
    public function __construct(Object $model)
    {
        $this->model = $model;
    }

    public function run()
    {
        $this->buildRelationshipMethodsArray();
        $this->build();
    }

    public function buildRelationshipMethodsArray()
    {
        foreach ($this->model->relationships as $relationship) {
            $relationshipClass = 'Joegabdelsater\\CatapultBase\\Builders\\Models\\' . ucfirst($relationship->relationship_method);

            $this->relationshipFactory = new RelationshipFactory(new $relationshipClass($relationship));
            $this->relationships[] = $this->relationshipFactory->build();
        }
    }

    public function getFinalRelationshipCode()
    {
        return implode('', $this->relationships);
    }

    public function build()
    {
        $modelName = $this->model->name;
        $imports = [
            'use Illuminate\Database\Eloquent\Model;',
            'use Illuminate\Database\Eloquent\Factories\HasFactory;',
        ];

        $uses = [
            'use HasFactory;',
        ];

        $importsCode = implode("\n", $imports);
        $usesCode = implode("\n\t", $uses);

        $extendsCode = 'Model';

        $implements = '';

        $modelContent = <<<PHP
            <?php
            namespace App\Models;

            $importsCode

            class $modelName extends Model
            {   
                $usesCode

                protected \$fillable = ['name', 'description', 'price'];
                // Add more properties and methods here as needed
                
                {$this->getFinalRelationshipCode()}
            }

            PHP;

        $modelDir = __DIR__ . '/../../../../../../app/Models';

        // Create the model file
        file_put_contents("$modelDir/$modelName.php", $modelContent);
    }
}
