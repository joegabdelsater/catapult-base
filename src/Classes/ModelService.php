<?php

namespace Joegabdelsater\CatapultBase\Classes;

use Joegabdelsater\CatapultBase\Builders\ClassGenerator;
use Joegabdelsater\CatapultBase\Builders\Models\ModelBuilder;

class ModelService
{
    public static function generate(Object $model)
    {
        $modelBuilder = new ModelBuilder($model);
        $modelGenerator = new ClassGenerator(filePath: config('directories.models'), fileName: $model->name . '.php', content: $modelBuilder->build());
        $modelGenerator->generate();

        $model->created = true;
        $model->updated = false;

        $model->save();
    }
}
