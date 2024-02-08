<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;

class RelationshipsController extends BaseController
{
    public function create()
    {
        $models = Model::with('relationships')->get();
        $relationships = config('relationships.supported');
        $relationshipMethods = config('relationships.function_parameters');
        return view('catapult::relationships.create', compact('models', 'relationships', 'relationshipMethods'));
    }

    public function store(Request $request)
    {
   

        return redirect()->back();
    }

    public function destroy()
    {

        return redirect()->back();
    }
}
