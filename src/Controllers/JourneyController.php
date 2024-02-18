<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Joegabdelsater\CatapultBase\Models\CatapultPackage;

class JourneyController extends BaseController
{
    public function index()
    {
        $packages = config('packages');
        return view('catapult::packages.index', compact('packages'));
    }

    public function addToComposer(Request $request)
    {
        $packages = $request->except('_token');
        $availablePackages = config('packages');
        $packagesKeys = array_keys($packages);
        $postInstallCommands = [];


        $files = new Filesystem();
        $workingPath = base_path();

        $composer = new Composer($files, $workingPath);

        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ($packagesKeys as $package) {
            $currentPackage = $availablePackages[$package];
            $dependencyKey = $currentPackage['dev'] ? 'require-dev' : 'require';

            if (!isset($composerJson[$dependencyKey][$currentPackage['composer_require']])) {
                $composerJson[$dependencyKey][$currentPackage['composer_require']] = $currentPackage['version'] ?? '*';

                $postInstallCommands = array_merge($postInstallCommands, $availablePackages[$package]['post_install']);

                CatapultPackage::create(['package_key' => $package]);
            }
        }

        //add the post install command for each package
        $composerJson['scripts']['catapult-post-install-cmd'] = $postInstallCommands;

        file_put_contents(base_path('composer.json'), json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $composer->dumpAutoloads();

        return redirect()->route('catapult.packages.add-package-success');
    }

    public function addPackageSuccess()
    {
        return view('catapult::packages.add-success');
    }

    public function successfullyGenerated()
    {
        return view('catapult::generate.success');
    }
}
