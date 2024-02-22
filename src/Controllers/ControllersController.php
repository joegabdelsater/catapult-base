<?php

namespace Joeabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joeabdelsater\CatapultBase\Models\CatapultController;
use Joeabdelsater\CatapultBase\Classes\ControllerService;

class ControllersController extends BaseController
{
    public function create()
    {
        $controllers = CatapultController::all();
        return view('catapult::controllers.create', compact('controllers'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required'
        ]);

        $name = ucfirst(trim($valid['name']));
        $name = str_replace('.php', '', $name);
        $name = str_replace('Controller', '', $name);
        $name = str_replace('controller', '', $name);


        $valid['name'] = $name . 'Controller';

        CatapultController::create($valid);
        return redirect()->route('catapult.controllers.create');
    }

    public function generate(CatapultController $controller)
    {
        $controller = CatapultController::with('routes')->find($controller->id);
        ControllerService::generate($controller);
        return redirect()->route('catapult.controllers.create');
    }

    public function generateAll()
    {
        $controllers = CatapultController::all();
        foreach ($controllers as $controller) {
            ControllerService::generate($controller);
        }
        return redirect()->route('catapult.controllers.create');
    }

    public function destroy(CatapultController $controller)
    {
        $controller->delete();
        return redirect()->route('catapult.controllers.create');
    }
}
