<?php
namespace Joegabdelsater\CatapultBase\Builders\Models;
use Joegabdelsater\CatapultBase\Builders\Models\RelationshipMethodBuilder;
use Joegabdelsater\CatapultBase\Models\Relationship;

class RelationshipFactory {
    private $relationshipMethodBuilder;
    
    public function __construct(RelationshipMethodBuilder $relationshipMethodBuilder) {
        $this->relationshipMethodBuilder = $relationshipMethodBuilder;
    }

    public function build(): string {
        return $this->relationshipMethodBuilder->build();
    }
}