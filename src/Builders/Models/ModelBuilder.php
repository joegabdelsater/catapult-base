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
        $packages = config('packages');
        $imports = [
            'use Illuminate\Database\Eloquent\Model;',
            'use Illuminate\Database\Eloquent\Factories\HasFactory;',
        ];

        $traits = [
            'use HasFactory;',
        ];

        $extends = ['Model'];
        $implements = [];
        $methods = [];
        $properties = [];

        $implementsCode = '';
        $extendsCode = '';
        $methodsCode = '';
        $propertiesCode = '';
        $importsCode = '';
        $traitsCode = '';

        if ($this->model->only_guard_id) {
            $properties[] = 'protected $guarded = [\'id\'];';
        }


        foreach ($this->model->packages as $package) {
            $package = $packages[$package];
            if (isset($package['model'])) {
                $imports = array_merge($imports, $package['model']['imports']);
                $traits = array_merge($traits, $package['model']['traits']);
                $extends = array_merge($extends, $package['model']['extends']);
                $implements = array_merge($implements, $package['model']['implements']);
                $methods = array_merge($methods, $package['model']['methods']);
                $properties = array_merge($properties, $package['model']['properties']);
            }
        }

        if (count($imports) > 0) {
            $importsCode = implode("\n", $imports);
        }

        if (count($traits) > 0) {
            $traitsCode = implode("\n\t", $traits);
        }

        if (count($extends) > 0) {
            $extendsCode .= " extends $extends[0]";
        }

        if (count($implements) > 0) {
            $implementsCode = implode(", ", $implements);
            $implementsCode .= " implements $implementsCode";
        }

        if (count($properties) > 0) {
            $propertiesCode = implode("\n\t", $properties);
        }

        if (count($methods) > 0) {
            $methodsCode = implode("\n\t", $methods);
        }


        $modelContent = <<<PHP
            <?php
            namespace App\Models;

            $importsCode

            class {$this->model->name} $extendsCode $implementsCode
            {   
                $traitsCode

                $propertiesCode

                /** Pacakges Methods */
                $methodsCode
                
                /**  Relationships */
                {$this->getRelationships()}
            }

            PHP;

        return $modelContent;
    }
}
