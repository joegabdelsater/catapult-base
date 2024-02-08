<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;

class RelationshipsController extends BaseController
{

    public function index() {
        $models = Model::all();
        return view('catapult::relationships.index', compact('models'));

    }
    public function create($modelId)
    {

        $model = Model::with('relationships')->find($modelId);
        $models = Model::all();
        $relationships = config('relationships.supported');
        $relationshipMethods = config('relationships.function_parameters');

        return view('catapult::relationships.create', compact('model', 'models', 'relationships', 'relationshipMethods'));
    }

    public function store(Request $request, Model $model)
    {
        $request->validate([
           'r.*' => 'array',
              'r.*.relationship_method_name' => 'required',
              'r.*.foreign_key' => 'sometimes',
              'r.*.local_key' => 'sometimes',
              'r.*.owner_key' => 'sometimes',
              'r.*.model' => 'required',
              'r.*.relationship_model' => 'required',
              'r.*.relationship_method' => 'required',
        ]);



        foreach ($request->r as $relationship) {
            $model->relationships()->create($relationship);
        }

        return redirect()->back();
    }

    public function destroy()
    {

        return redirect()->back();
    }
}
