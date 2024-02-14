<?php

namespace Joegabdelsater\CatapultBase\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\CatapultController;
use Joegabdelsater\CatapultBase\Models\Model;

class RoutesController extends BaseController
{

    public function index()
    {
        $controllers = CatapultController::with('routes')->get();
        return view('catapult::controllers.index', compact('controllers'));
    }
    public function create($controllerId)
    {
        $controller = CatapultController::find($controllerId);
        $availableRoutes = config('catapult.routes');
 
        return view('catapult::routes.create', compact('controller'));
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

        return redirect()->route('catapult.relationships.index');
    }

    public function destroy( $modelId, $relationshipId)
    {       

        $model = Model::find($modelId);
        $model->updated = true; 
        
        $model->save();

        Relationship::destroy($relationshipId);
        return response()->json(['message' => 'Relationship deleted']);
    }
}
