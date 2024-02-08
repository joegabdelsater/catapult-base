<?php
namespace Joegabdelsater\CatapultBase\Builders\Models;
use Joegabdelsater\CatapultBase\Models\Relationship;
interface RelationshipMethodBuilder
{
    public function build(): string;
}