<?php

namespace Joegabdelsater\CatapultBase\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\CatapultModel;
use Joegabdelsater\CatapultBase\Models\CatapultRelationship;

class RelationshipsController extends BaseController
{

    public function index()
    {   
        $models = CatapultModel::with('relationships')->get();
        return view('catapult::relationships.index', compact('models'));
    }
    public function create($modelId)
    {
        $model = CatapultModel::find($modelId);
        $models = CatapultModel::all();

        $supportedRelationships = config('relationships.supported');
        $relationshipMethodParameters = config('relationships.function_parameters');

        $exitsting = [];

        foreach($supportedRelationships as $key => $relationship){
            $existing[$key] = CatapultRelationship::where([
                'relationship' => $key,
                'catapult_model_id' => $modelId
            ])->get();
        }

        return view('catapult::relationships.create', compact('model', 'models', 'supportedRelationships', 'relationshipMethodParameters', 'existing'));
    }

    public function store(Request $request, CatapultModel $model)
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

        return redirect()->route('catapult.relationships.index');
    }

    public function destroy( $modelId, $relationshipId)
    {       

        $model = CatapultModel::find($modelId);
        $model->updated = true; 
        
        $model->save();

        CatapultRelationship::destroy($relationshipId);
        return response()->json(['message' => 'Relationship deleted']);
    }
}
