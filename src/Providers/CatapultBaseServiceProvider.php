<?php

namespace Joegabdelsater\CatapultBase\Providers;

use Illuminate\Support\ServiceProvider;

class CatapultBaseServiceProvider extends ServiceProvider
{

    /** @todo add instructions to publish js */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'catapult');
        $this->loadMigrationsFrom([__DIR__ . '/../migrations']);
        $this->mergeConfigFrom(__DIR__ . '/../config/relationships.php','relationships');
        $this->mergeConfigFrom(__DIR__ . '/../config/directories.php','directories');
        $this->mergeConfigFrom(__DIR__ . '/../config/migrations.php','migrations');
        $this->mergeConfigFrom(__DIR__ . '/../config/routes.php','routes');




        $this->publishes([
            __DIR__ . '/../assets/js/' => public_path('joegabdelsater/catapult-base/js/'),
        ], 'catapult-base');
    }
}
