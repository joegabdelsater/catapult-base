<?php

namespace Joeabdelsater\CatapultBase\Classes;

use Joeabdelsater\CatapultBase\Builders\ClassGenerator;
use Joeabdelsater\CatapultBase\Builders\Models\ModelBuilder;

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
