<?php

namespace Joegabdelsater\CatapultBase\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class JourneyController extends BaseController
{
    public function index()
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

    /** heredoc syntax */
    public function createFile()
    {

        $modelName = 'Product'; // Dynamic or static model name
        $imports = [
            'use Illuminate\Database\Eloquent\Model;',
            'use Illuminate\Database\Eloquent\Factories\HasFactory;',
            'use Spatie\Translatable\HasTranslations;',
        ];

        $uses = [
            'use HasFactory;',
            'use HasTranslations;',
        ];

        $importsCode = implode("\n", $imports);

        $usesCode = implode("\n\t", $uses);


        $modelContent = <<<PHP
            <?php
            namespace App\Models;

            $importsCode

            class $modelName extends Model
            {   
                $usesCode

                protected \$fillable = ['name', 'description', 'price'];
                // Add more properties and methods here as needed
            }

            PHP;


        $modelDir = __DIR__ . '/../../../../../app/Models';

        // Create the model file
        file_put_contents("$modelDir/$modelName.php", $modelContent);
    }
}
