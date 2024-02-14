<?php

namespace Joegabdelsater\CatapultBase\Builders\Routes;

use Joegabdelsater\CatapultBase\Interfaces\Builder;
use Joegabdelsater\CatapultBase\Models\CatapultController;

class RouteBuilder implements Builder
{
    public $controllers;


    public function __construct()
    {
        $this->controllers = CatapultController::with('routes')->get();

    }

    public function getControllerRoutesCode($controller) {

    }

    public function build(): string
    {
        $imports = [
            'use Illuminate\Support\Facades\Route;'
        ];

        foreach($this->controllers as $controller) {
            $imports[] = "use App\Http\Controllers\{$controller->name};";
            $this->getControllerRoutesCode($controller);
        }



     
        $importsCode = implode("\n", $imports);


        $implements = '';

        $code = <<<PHP
            <?php
            $importsCode




            PHP;

        return $code;
    }
}
