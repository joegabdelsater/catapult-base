<?php

namespace Joegabdelsater\CatapultBase\Builders\Models;

use Joegabdelsater\CatapultBase\Interfaces\Builder;

class ModelBuilder implements Builder
{
    public $model;
    public $relationshipFactory;
    public $relationships = [];
    public function __construct(Object $model)
    {
        $this->model = $model;
    }

 
    public function getRelationships(): string
    {
        foreach ($this->model->relationships as $relationship) {
            $this->relationshipFactory = new RelationshipBuilder($relationship);
            $this->relationships[] = $this->relationshipFactory->build();
        }

        return implode('', $this->relationships);
    }


    public function build(): string
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

            class {$this->model->name} extends Model
            {   
                $usesCode

                protected \$fillable = ['name', 'description', 'price'];
                
                {$this->getRelationships()}
            }

            PHP;

        return $modelContent;
    }
}
