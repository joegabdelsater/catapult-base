<?php
namespace Joeabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Illuminate\Support\Str;
use Joeabdelsater\CatapultBase\Classes\ModelService;
use Joeabdelsater\CatapultBase\Models\CatapultPackage;

class FieldsController extends BaseController {

    public function create($modelId) {
        $model = CatapultModel::find($modelId);
        $models = CatapultModel::all();
        return view('catapult::fields.create', compact('model', 'models'));
    }

    public function store(Request $request, $modelId) {
        dd($request->all());
    }
}