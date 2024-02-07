<?php

namespace Joegabdelsater\CatapultBase\Providers;

use Illuminate\Support\ServiceProvider;

class CatapultBaseServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'catapult');
        $this->loadMigrationsFrom([__DIR__ . '/../migrations']);
        $this->mergeConfigFrom(
            __DIR__ . '/../config/relationships.php',
            'relationships'
        );
    }
}
