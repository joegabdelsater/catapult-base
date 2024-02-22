<?php

namespace Joegabdelsater\CatapultBase\Builders\Models;

use Joegabdelsater\CatapultBase\Models\CatapultRelationship;
use Joegabdelsater\CatapultBase\Interfaces\Builder;

class RelationshipBuilder implements Builder
{
    private $relationship;

    public function __construct(CatapultRelationship $relationship)
    {
        $this->relationship = $relationship;
    }


    public function build(): string
    {   
        $methodParameters = '';

        if($this->relationship->relationship !== 'polymorphic_morph_to') {
            $methodParameters = $this->relationship->relationship_model . $this->generateKeys();
        }

        return  <<<PHP
                public function {$this->relationship->relationship_method_name}() {
                    return \$this->{$this->relationship->relationship_method}($methodParameters);
                }
    
            PHP;
    }

    public function generateKeys(): string
    {
        $availableKeys = [
            'foreignKey' => $this->relationship->foreign_key,
            'localKey' => $this->relationship->local_key,
            'ownerKey' => $this->relationship->owner_key,
            'table' => $this->relationship->table,
            'foreignPivotKey' => $this->relationship->model_foreign_key,
            'relatedPivotKey' => $this->relationship->related_model_foreign_key,
            'name' => $this->relationship->polymorphic_relation,
        ];

        $keys = [];

        foreach ($availableKeys as $key => $value) {
            if (!empty($value)) {
                $keys[] = $key . ':' . "'" . $value . "'";
            }
        }

        if (empty($keys)) {
            return '';
        }

        return ', ' . implode(', ', $keys);
    }
}
