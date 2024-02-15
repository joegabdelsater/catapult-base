<?php

namespace Joegabdelsater\CatapultBase\Builders\Routes;

use Joegabdelsater\CatapultBase\Interfaces\Builder;
use Joegabdelsater\CatapultBase\Models\CatapultController;

class RouteBuilder implements Builder
{
    public $controllers;
    public $routes = ['api' => [], 'web' => []];
    public $imports = [
        'api' => ['use Illuminate\Support\Facades\Route;'],
        'web' => ['use Illuminate\Support\Facades\Route;']
    ];

    public function __construct()
    {
        $this->controllers = CatapultController::with('routes')->get();
    }

    public function getControllerRoutesCode($controller)
    {
        foreach ($controller->routes as $route) {
            $this->routes[$route->route_type][] = $this->generateRouteCode($route, $controller);

            if ($route->route_type == 'api') {
                $this->imports['api'][] = "use App\Http\Controllers\\{$controller->name};";
            } else {
                $this->imports['web'][] = "use App\Http\Controllers\\{$controller->name};";
            }
        }

        $this->imports['api'] = array_unique($this->imports['api']);
        $this->imports['web'] = array_unique($this->imports['web']);
    }

    public function generateRouteCode($route, $controller)
    {
        $code = "Route::{$route->method}('{$route->route_path}', [{$controller->name}::class, '{$route->controller_method}'])";

        if ($route->route_name) {
            $code .= "->name('{$route->route_name}')";
        }

        $code .= ';';

        return $code;
    }

    public function build(): array
    {
        $apiRoutes = '';
        $webRoutes = '';

        foreach ($this->controllers as $controller) {
            $this->getControllerRoutesCode($controller);
        }


        $apiRoutesImportsCode = implode("\n", $this->imports['api']);
        $webRoutesImportsCode = implode("\n", $this->imports['web']);

        $apiRoutesCode = implode("\n", $this->routes['api']);
        $webRoutesCode = implode("\n", $this->routes['web']);

        $apiRoutesCode = <<<PHP
            <?php
            $apiRoutesImportsCode
            
            $apiRoutesCode
            PHP;

        $webRoutesCode = <<<PHP
            <?php
            $webRoutesImportsCode

            $webRoutesCode
            PHP;

        return [
            'api' => $apiRoutesCode,
            'web' => $webRoutesCode
        ];
    }
}
