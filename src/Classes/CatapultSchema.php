<?php

namespace Joeabdelsater\CatapultBase\Classes;

use Illuminate\Database\Schema\Blueprint;

class CatapultSchema
{
    public static function create($tableName, $callback)
    {
        $table = new Blueprint($tableName);
        $callback($table);

        $validationRules = [];

        foreach ($table->getColumns() as $column) {

            if ($column->validation) {
                $validationRules[$column->name] = $column->validation;
            }
        }

        return $validationRules;
    }
}
