<?php
namespace Joegabdelsater\CatapultBase\Builders\Models;
use Joegabdelsater\CatapultBase\Models\Relationship;
use Joegabdelsater\CatapultBase\Builders\Models\BaseRelationship;

class BelongsTo extends BaseRelationship implements RelationshipMethodBuilder
{
    public function build(): string
    {
        return  <<<PHP
            public function {$this->relationship->relationship_method_name}() {
                return \$this->belongsTo({$this->relationship->relationship_model}{$this->generateKeys()});
            }

        PHP;
    }
}