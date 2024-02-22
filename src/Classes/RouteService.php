<?php

namespace Joeabdelsater\CatapultBase\Classes;

use Joeabdelsater\CatapultBase\Builders\ClassGenerator;
use Joeabdelsater\CatapultBase\Builders\Routes\RouteBuilder;

class RouteService
{
    public static function generate()
    {
        if(!is_dir(config('directories.catapult_routes'))){
            mkdir(config('directories.catapult_routes'), 0755, true);
        }
        
        $builder = new RouteBuilder();
        $code = $builder->build();

        $apiRoutesGenerator = new ClassGenerator(
            filePath: config('directories.catapult_routes'),
            fileName: 'api.php',
            content: $code['api']
        );

        $apiRoutesGenerator->generate()
            ->appendToFile(config('directories.api_routes_file'), "\n\n require __DIR__ . '/catapult/api.php';");


        $webRoutesGenerator = new ClassGenerator(
            filePath: config('directories.catapult_routes'),
            fileName: 'web.php',
            content: $code['web']

        );
        $webRoutesGenerator->generate()
            ->appendToFile(config('directories.web_routes_file'), "\n\n require __DIR__ . '/catapult/web.php';");
    }
}
