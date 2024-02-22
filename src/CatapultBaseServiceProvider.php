<?php

namespace Joeabdelsater\CatapultBase;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Joeabdelsater\CatapultBase\Models\CatapultController;
use Joeabdelsater\CatapultBase\Models\CatapultMigration;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Joeabdelsater\CatapultBase\Console\SetupPackages;


class CatapultBaseServiceProvider extends ServiceProvider
{

    /** @todo add instructions to publish js */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'catapult');
        $this->loadMigrationsFrom([__DIR__ . '/migrations']);
        $this->mergeConfigFrom(__DIR__ . '/config/relationships.php', 'relationships');
        $this->mergeConfigFrom(__DIR__ . '/config/directories.php', 'directories');
        $this->mergeConfigFrom(__DIR__ . '/config/migrations.php', 'migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/packages.php', 'packages');
        $this->mergeConfigFrom(__DIR__ . '/config/routes.php', 'routes');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupPackages::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/assets/js/' => public_path('Joeabdelsater/catapult-base/js/'),
        ], 'catapult-base');

        if (!app()->runningInConsole()) {
            View::share([
                'alerts' => [
                    'models' => CatapultModel::where('updated', true)->get()->count() > 0,
                    'controllers' => CatapultController::where('updated', true)->get()->count() > 0,
                    'validations' => CatapultMigration::where('updated', true)->get()->count() > 0,
                ]
            ]);
        }
        
    }
}
