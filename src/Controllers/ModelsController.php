<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\Model;
use Illuminate\Support\Str;
use Joegabdelsater\CatapultBase\Classes\ModelService;
class ModelsController extends BaseController
{
    public function create()
    {
        $models = Model::all();
        return view('catapult::models.create', compact('models'));
    }

    public function generate(Model $model)
    {
        $model = Model::with('relationships')->find($model->id);
        ModelService::generate($model);

        return redirect()->back();
    }

    public function generateAll() {
        $models = Model::with('relationships')->get();

        foreach ($models as $model) {
            ModelService::generate($model);
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|unique:models',
            'table_name' => 'nullable|unique:models,table_name',
            'only_guard_id' => 'nullable',
            'has_translations' => 'nullable',
            'has_validation_request' => 'nullable',
        ]);

        Model::create([
            'name' => trim(ucfirst($valid['name'])),
            'table_name' => $valid['table_name'] ?? $this->getTableName(Str::snake($valid['name'])),
            'only_guard_id' => $request->has('only_guard_id'),
            'has_translations' => $request->has('has_translations'),
            'has_validation_request' => $request->has('has_validation_request'),
        ]);

        return redirect()->back();
    }

    public function getTableName($snakeCaseTableName)
    {
        return Str::plural($snakeCaseTableName);
    }

    public function destroy(Model $model)
    {
        Model::destroy($model->id);
        return redirect()->back();
    }
}
