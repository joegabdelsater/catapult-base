<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;

class MigrationsController extends BaseController
{

    public function index() {
        $models = Model::with('migrations')->get();
        return view('catapult::migrations.index', compact('models'));
    }

public function create(Model $model)
    {
        return view('catapult::migrations.create', compact('model'));
    }

    public function store(Request $request, Model $model)
    {
       
        $valid = $request->validate([
            'name' => 'required|unique:models',
            'only_guard_id' => 'nullable',
            'has_translations' => 'nullable',
        ]);

        Model::create([
            'name' => trim(ucfirst($valid['name'])),
            'only_guard_id' => $request->has('only_guard_id'),
            'has_translations' => $request->has('has_translations'),
        ]);

        return redirect()->back();
    }

    public function destroy(Model $model)
    {
        Model::destroy($model->id);
        return redirect()->back();
    }
}
