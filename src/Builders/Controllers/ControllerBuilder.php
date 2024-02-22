<?php

namespace Joeabdelsater\CatapultBase\Builders\Controllers;

use Joeabdelsater\CatapultBase\Interfaces\Builder;
use Joeabdelsater\CatapultBase\Models\CatapultModel;

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
            'imports' => implode("\n", $imports),
            'methods' => implode("\n\n\t", $methods)
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
            $isModel = CatapultModel::where('name', ucfirst($match))->first();

            if ($isModel) {
                $imports[] = 'use App\Models\\' . ucfirst($match) . ';';
                $parameters[] = ucfirst($match) . ' $' . $match;
            } else {
                $parameters[] = '$' . $match;
            }
        }

        $parametersCode = implode(', ', $parameters);
        $returnCode = $route->route_type == 'api' ? "response()->json([])" : "view('{$route->controller_method}')";
        $code = <<<PHP
        public function $route->controller_method($parametersCode) {
                return $returnCode;
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
