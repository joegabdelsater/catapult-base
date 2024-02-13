<?php

namespace Joegabdelsater\CatapultBase\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;
use Joegabdelsater\CatapultBase\Models\Relationship;

class RelationshipsController extends BaseController
{

    public function index()
    {
        $models = Model::with('relationships')->get();
        return view('catapult::relationships.index', compact('models'));
    }
    public function create($modelId)
    {
        $model = Model::find($modelId);
        $models = Model::all();

        $supportedRelationships = config('relationships.supported');
        $relationshipMethodParameters = config('relationships.function_parameters');

        $exitsting = [];

        foreach($supportedRelationships as $key => $relationship){
            $existing[$key] = Relationship::where([
                'relationship' => $key,
                'model_id' => $modelId
            ])->get();
        }

        return view('catapult::relationships.create', compact('model', 'models', 'supportedRelationships', 'relationshipMethodParameters', 'existing'));
    }

    public function store(Request $request, Model $model)
    {
        $request->validate([
            'r.*' => 'array',
            'r.*.relationship' => 'required',
            'r.*.relationship_method_name' => 'required',
            'r.*.foreign_key' => 'sometimes',
            'r.*.local_key' => 'sometimes',
            'r.*.owner_key' => 'sometimes',
            'r.*.model' => 'required',
            'r.*.relationship_model' => 'required',
            'r.*.relationship_method' => 'required',
            'r.*.id' => 'sometimes',
        ]);


        foreach ($request->r as $relationship) {
            if (isset($relationship['id'])) {
                $model->relationships()->find($relationship['id'])->update($relationship);
                continue;
            }

            $model->relationships()->create($relationship);
        }

        $model->updated = true;
        $model->save();
        
        return redirect()->back();
    }

    public function destroy($relationshipId)
    {
        Relationship::destroy($relationshipId);
        return response()->json(['message' => 'Relationship deleted']);
    }
}
