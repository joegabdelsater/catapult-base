<?php

namespace Joegabdelsater\CatapultBase\Builders\Controllers;

use Joegabdelsater\CatapultBase\Interfaces\Builder;
use Joegabdelsater\CatapultBase\Models\CatapultController;
class ControllerBuilder implements Builder
{
    public $controller;
    public $relationshipFactory;
    public $relationships = [];
    public function __construct(Object $controller)
    {
        $this->controller = $controller;
    }

    public function buildMethodsFromRoutes() {
        return [];
    }



    public function build(): string
    {   
        $methodsCode = implode('\n',$this->buildMethodsFromRoutes());
        $code =  <<<PHP
        <?php
        namespace App\Http\Controllers;
        
        use Illuminate\Http\Request;
        
        class {$this->controller->name} extends Controller
        {
            $methodsCode
        }

        PHP;

        return $code; 
    }
}
 