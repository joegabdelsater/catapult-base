<?php

namespace Joeabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Illuminate\Support\Str;
use Joeabdelsater\CatapultBase\Classes\ModelService;
use Joeabdelsater\CatapultBase\Models\CatapultPackage;

class ModelsController extends BaseController
{
    public function create()
    {
        $availablePackages = config('packages');
        $packages = CatapultPackage::all()->pluck('package_key');
        $currentPackages = [];

        foreach ($packages as $package) {
            $currentPackages[$package] = $availablePackages[$package];
        }

        $models = CatapultModel::all();

        return view('catapult::models.create', compact('models', 'currentPackages'));
    }

    public function generate(CatapultModel $model)
    {
        $model = CatapultModel::with('relationships')->find($model->id);
        ModelService::generate($model);

        return redirect()->back();
    }

    public function generateAll()
    {
        $models = CatapultModel::with('relationships')->get();

        foreach ($models as $model) {
            ModelService::generate($model);
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {

        $valid = $request->validate([
            'name' => 'required|unique:catapult_models',
            'table_name' => 'nullable|unique:models,table_name',
            'only_guard_id' => 'nullable',
            'packages' => 'nullable|array',
            'packages.*' => 'string'
        ]);


        CatapultModel::create([
            'name' => trim(ucfirst($valid['name'])),
            'table_name' => $valid['table_name'] ?? $this->getTableName(Str::snake($valid['name'])),
            'only_guard_id' => $request->has('only_guard_id'),
            'packages' => isset($valid['packages']) ? array_keys($valid['packages']) : null

        ]);

        return redirect()->back();
    }

    public function getTableName($snakeCaseTableName)
    {
        return Str::plural($snakeCaseTableName);
    }

    public function destroy(CatapultModel $model)
    {
        CatapultModel::destroy($model->id);
        return redirect()->back();
    }
}
