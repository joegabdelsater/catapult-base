<?php

namespace Joeabdelsater\CatapultBase\Builders\Migrations;

use Joeabdelsater\CatapultBase\Interfaces\Builder;
use Joeabdelsater\CatapultBase\Models\CatapultMigration;

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
