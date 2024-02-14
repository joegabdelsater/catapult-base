<?php

namespace Joegabdelsater\CatapultBase\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Route;
use Joegabdelsater\CatapultBase\Models\CatapultController;


class RoutesController extends BaseController
{

    public function index()
    {
        $controllers = CatapultController::with('routes')->get();
        return view('catapult::controllers.index', compact('controllers'));
    }
    public function create($controllerId)
    {
        $controller = CatapultController::with('routes')->find($controllerId);
        $supportedRoutes = config('routes.supported');

        $existing = [];

        foreach ($supportedRoutes as $key => $route) {
            $existing[$route] = $controller->routes->filter(function ($value, $key) use ($route) {
                return $value->method == $route;
            });
        }

        return view('catapult::routes.create', compact('controller', 'supportedRoutes', 'existing'));
    }

    public function store(Request $request, CatapultController $controller)
    {
        $valid = $request->validate([
            'method' => 'required',
            'route_name' => 'nullable',
            'route_path' => 'required',
            'controller_method' => 'required',
        ]);

        $valid['controller_id'] = $controller->id;

        $controller->routes()->create($valid);

        $controller->updated = true;
        $controller->save();

        return redirect()->back();
    }

    public function destroy(CatapultController $controller, Route $route)
    {
        $route->delete();
        $controller->updated = true;
        $controller->save();

        return response()->json(['message' => 'Route deleted']);
    }
}
