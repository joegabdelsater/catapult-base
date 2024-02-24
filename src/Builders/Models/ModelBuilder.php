<?php

namespace Joeabdelsater\CatapultBase\Builders\Models;

use Joeabdelsater\CatapultBase\Interfaces\Builder;

class ModelBuilder implements Builder
{
    public $model;
    public $relationshipFactory;
    public $relationships = [];

    public $imports = [
        'use Illuminate\Database\Eloquent\Model;',
        'use Illuminate\Database\Eloquent\Factories\HasFactory;',
    ];

    public $traits = [
        'use HasFactory;',
    ];

    public $extends = 'Model';
    public $implements = [];
    public $methods = [];
    public $properties = [];

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
        $this->mergeWithPackageConfiguration();
        $this->mergeWithModelClassDetails();
        $this->unify();

        $code = $this->generateDetailsCode();

        $modelContent = <<<PHP
            <?php
            namespace App\Models;

            {$code['imports']}

            class {$this->model->name} {$code['extends']} {$code['implements']}
            {
                {$code['traits']}

                {$code['properties']}

                /** Pacakges Methods */
                {$code['methods']}

                /**  Relationships */
                {$this->getRelationships()}
            }

            PHP;


        return $modelContent;
    }

    public function generateDetailsCode()
    {
        $code = [
            'imports' => '',
            'traits' => '',
            'extends' => '',
            'implements' => '',
            'methods' => '',
            'properties' => '',
        ];


        if (count($this->imports) > 0) {
            $code['imports'] = implode("\n", $this->imports);
        }

        if (count($this->traits) > 0) {
            $code['traits'] = implode("\n\t", $this->traits);
        }

        if (!empty($this->extends)) {
            $code['extends'] = " extends $this->extends";
        }

        if (count($this->implements) > 0) {
            $code['implements'] = " implements " .  implode(", ", $this->implements);
        }

        if (count($this->properties) > 0) {
            $code['properties'] = implode("\n\t", $this->properties);
        }

        if (count($this->methods) > 0) {
            $code['methods'] = implode("\n\t", $this->methods);
        }   


        return $code;
    }
    public function mergeWithPackageConfiguration()
    {
        if ($this->model->packages) {
            $packages = config('packages');

            foreach ($this->model->packages as $package) {
                $package = $packages[$package];
                if (isset($package['model'])) {
                    $this->imports = array_merge($this->imports, $package['model']['imports']);
                    $this->traits = array_merge($this->traits, $package['model']['traits']);
                    $$this->extends =  $package['model']['extends'];
                    $$this->implements = array_merge($$this->implements, $package['model']['implements']);
                    $this->methods = array_merge($this->methods, $package['model']['methods']);
                    $this->properties = array_merge($this->properties, $package['model']['properties']);
                }
            }
        }
    }

    public function mergeWithModelClassDetails()
    {
        if ($this->model->extends) {
            $this->extends = $this->model->extends;
        }
        
        if ($this->model->imports) {
            $this->imports = array_merge($this->imports, $this->model->imports);
        }
        
        if ($this->model->traits) {
            $this->traits = array_merge($this->traits, $this->model->traits);
        }
        
        if ($this->model->only_guard_id) {
            $this->properties[] = 'protected $guarded = [\'id\'];';
        }
        
        if ($this->model->properties) {
            $this->properties = array_merge($this->properties, $this->model->properties);
        }
        
        if ($this->model->implements) {
            $this->implements = array_merge($this->implements, $this->model->implements);
        }

    }

    public function unify() {
        $this->imports = array_unique($this->imports);
        $this->traits = array_unique($this->traits);
        $this->implements = array_unique($this->implements);
        $this->methods = array_unique($this->methods);
        $this->properties = array_unique($this->properties);
    }
}
