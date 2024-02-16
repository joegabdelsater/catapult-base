<?php

use Illuminate\Support\Facades\Route;
use Joegabdelsater\CatapultBase\Controllers\JourneyController;
use Joegabdelsater\CatapultBase\Controllers\MigrationsController;
use Joegabdelsater\CatapultBase\Controllers\ModelsController;
use Joegabdelsater\CatapultBase\Controllers\RelationshipsController;
use Joegabdelsater\CatapultBase\Controllers\ControllersController;
use Joegabdelsater\CatapultBase\Controllers\RoutesController;

Route::prefix('catapult')
    ->as('catapult.')
    ->middleware('web')
    ->group(function () {
        Route::get('/', [JourneyController::class, 'index'])->name('welcome');
        Route::post('packages', [JourneyController::class, 'addToComposer'])->name('packages.add-to-composer');
        Route::get('add-package-success', [JourneyController::class, 'addPackageSuccess'])->name('packages.add-package-success');

        Route::controller(ModelsController::class)->group(function () {
            Route::get('/models', 'create')->name('models.create');
            Route::post('/models', 'store')->name('models.store');
            Route::get('/models/{model}/generate', 'generate')->name('models.generate');
            Route::get('/models/generate', 'generateAll')->name('models.generate-all');
            Route::post('/models/{model}/delete', 'destroy')->name('models.destroy');
        });

        Route::controller(RelationshipsController::class)->group(function () {
            Route::get('/relationships', 'index')->name('relationships.index');
            Route::get('/models/{modelId}/relationships', 'create')->name('relationships.create');
            Route::post('/relationships/{model}', 'store')->name('relationships.store');
            Route::post('/model/{modelId}/relationships/{relationshipId}/destroy', 'destroy')->name('relationships.destroy');
        });

        Route::controller(MigrationsController::class)->group(function () {
            Route::get('/migrations', 'index')->name('migrations.index');
            Route::get('/models/{model}/migrations', 'create')->name('migrations.create');
            Route::post('/models/{model}/migrations', 'store')->name('migrations.store');
            Route::delete('/models/{migration}/migrations', 'destroy')->name('migrations.destroy');
            Route::post('migration/{model}/generate', 'generate')->name('migrations.generate');
        });

        Route::controller(ControllersController::class)->group(function () {
            Route::get('/controllers', 'create')->name('controllers.create');
            Route::post('/controllers', 'store')->name('controllers.store');
            Route::get('/controllers/{controller}/generate', 'generate')->name('controllers.generate');
            Route::get('/controllers/generate', 'generateAll')->name('controllers.generate-all');
            Route::post('/controllers/{controller}/delete', 'destroy')->name('controllers.destroy');
        });

        Route::controller(RoutesController::class)->group(function () {
            Route::get('/routes', 'index')->name('routes.index');
            Route::get('controller/{controllerId}/routes/{type}', 'create')->name('routes.create');
            Route::post('/controller/{controller}/routes', 'store')->name('routes.store');
            Route::get('/routes/generate', 'generateAll')->name('routes.generate-all');
            Route::post('/controller/{controller}/route/{route}/destroy', 'destroy')->name('routes.destroy');
        });

    });
