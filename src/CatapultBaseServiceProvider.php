<?php

namespace Joeabdelsater\CatapultBase;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Joeabdelsater\CatapultBase\Models\CatapultController;
use Joeabdelsater\CatapultBase\Models\CatapultMigration;
use Joeabdelsater\CatapultBase\Models\CatapultModel;
use Joeabdelsater\CatapultBase\Console\SetupPackages;
use Joeabdelsater\CatapultBase\Console\CatapultInstall;
use Joeabdelsater\CatapultBase\Console\TranslateFilamentServiceProvider;


class CatapultBaseServiceProvider extends ServiceProvider
{

    /** @todo add instructions to publish js */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'catapult');
        $this->loadMigrationsFrom([__DIR__ . '/Migrations']);
        $this->mergeConfigFrom(__DIR__ . '/Config/relationships.php', 'relationships');
        $this->mergeConfigFrom(__DIR__ . '/Config/directories.php', 'directories');
        $this->mergeConfigFrom(__DIR__ . '/Config/migrations.php', 'migrations');
        $this->mergeConfigFrom(__DIR__ . '/Config/packages.php', 'packages');
        $this->mergeConfigFrom(__DIR__ . '/Config/routes.php', 'routes');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupPackages::class,
                CatapultInstall::class,
                TranslateFilamentServiceProvider::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/Assets/js/' => public_path('Joeabdelsater/catapult-base/js/'),
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
