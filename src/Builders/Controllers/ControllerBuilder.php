<?php

namespace Joegabdelsater\CatapultBase\Builders\Controllers;

use Joegabdelsater\CatapultBase\Interfaces\Builder;
use Joegabdelsater\CatapultBase\Models\CatapultController;
use Joegabdelsater\CatapultBase\Models\Model;

class ControllerBuilder implements Builder
{
    public $controller;
    public $routes;

    public function __construct(Object $controller)
    {
        $this->controller = $controller;
        $this->routes = $controller->routes;
    }

    public function buildMethodsFromRoutes()
    {
        $imports = [];
        $methods = [];

        foreach ($this->routes as $k => $route) {
            $result = $this->buildMethod($route);
            $methods[] = $result['code'];
            $imports = array_merge($imports, $result['imports']);
        }
        $imports = array_unique($imports);

        return [
            'imports' => implode('\n', $imports),
            'methods' => implode('\n\n', $methods)
        ];
    }

    public function buildMethod($route)
    {

        $parameters = [];
        $imports = [];

        preg_match_all('/\{(.*?)\}/', $route->route_path, $matches);

        if (in_array($route->method, ['post', 'put', 'patch'])) {
            $parameters[] = 'Request $request';
        }

        foreach ($matches[1] as $match) {
            $isModel = Model::where('name', ucfirst($match))->first();

            if ($isModel) {
                $imports[] = 'use App\Models\\' . ucfirst($match) . ';';
                $parameters[] = ucfirst($match) . ' $' . $match;
            } else {
                $parameters[] = '$' . $match;
            }
        }

        $parametersCode = implode(', ', $parameters);

        $code = <<<PHP
        public function $route->controller_method($parametersCode) {
                return; // don't forget to return something!
            }
        PHP;

        return [
            'code' => $code,
            'imports' => $imports
        ];
    }


    public function build(): string
    {
        $methodsCode =  $this->buildMethodsFromRoutes();

        $imports = $methodsCode['imports'];
        $methodsCode = $methodsCode['methods'];


        $code =  <<<PHP
        <?php
        namespace App\Http\Controllers;
        
        $imports
        use Illuminate\Http\Request;
        
        class {$this->controller->name} extends Controller
        {
            $methodsCode
        }

        PHP;

        return $code;
    }
}
