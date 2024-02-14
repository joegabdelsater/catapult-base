<?php

namespace Joegabdelsater\CatapultBase\Classes;

use Joegabdelsater\CatapultBase\Builders\ClassGenerator;
use Joegabdelsater\CatapultBase\Builders\Controllers\ControllerBuilder;

class ControllerService
{
    public static function generate(Object $controller)
    {
        $controllerBuilder = new ControllerBuilder($controller);
        $controllerGenerator = new ClassGenerator(filePath: config('directories.controllers'), fileName: $controller->name . '.php', content: $controllerBuilder->build());
        $controllerGenerator->generate();

        $controller->created = true;
        $controller->updated = false;

        $controller->save();
    }
}
