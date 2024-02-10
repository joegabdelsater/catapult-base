<?php

namespace Joegabdelsater\CatapultBase\Builders\Migrations;

use Joegabdelsater\CatapultBase\Interfaces\Builder;
use Joegabdelsater\CatapultBase\Models\CatapultMigration;
class MigrationBuilder implements Builder
{
    public $migration;
    public $relationshipFactory;
    public $relationships = [];
    public function __construct(CatapultMigration $migration)
    {
        $this->migration = $migration;
    }



    public function build(): string
    {
        return $this->migration->migration_code;
    }
}
