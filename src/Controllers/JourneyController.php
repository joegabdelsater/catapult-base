<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

class JourneyController extends BaseController
{
    public function index()
    {
        return view('catapult::welcome');
    }
    public function installDependencies()
    {
        /** Check Process documentation in laravel
         * https://laravel.com/docs/10.x/processes
         * Used to automate packages installation and automating package installation commands
         */
        Process::run('chmod -R +x ' . __DIR__ . '/../Scripts');

        $process = Process::start(__DIR__ . '/../Scripts/spatie_translatable.sh', function (string $type, string $output) {
            echo $output;
        });

        $result = $process->wait();
    }

    public function composer()
    {
        $files = new Filesystem();
        $workingPath = base_path(); 

        $composer = new Composer($files, $workingPath);

        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
        
        //loop over packages add add them here
        $composerJson['require']['new/package'] = '1.0.0';

        //add the post install command for each package
        $composerJson['scripts']['catapult-post-install-cmd'] = [
            '@php artisan migrate --force',
        ];

        file_put_contents(base_path('composer.json'), json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $composer->dumpAutoloads();

        // After all is done run: `composer install`, then run `composer run-script catapult-post-install-cmd`
    }

    public function successfullyGenerated()
    {
        return view('catapult::generate.success');
    }
}
