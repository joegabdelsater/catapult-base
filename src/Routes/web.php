<?php

use Illuminate\Support\Facades\Route;
use Joegabdelsater\CatapultBase\Controllers\JourneyController;
use Joegabdelsater\CatapultBase\Controllers\ModelsController;
use Joegabdelsater\CatapultBase\Controllers\RelationshipsController;

Route::prefix('catapult')
    ->as('catapult.')
    ->middleware('web')
    ->group(function () {
        Route::get('/', [JourneyController::class, 'index'])->name('welcome');
        Route::get('/models', [ModelsController::class, 'create'])->name('models.create');
        Route::post('/models', [ModelsController::class, 'store'])->name('models.store');
        Route::post('/models/{model}/delete', [ModelsController::class, 'destroy'])->name('models.destroy');

        Route::get('/relationships', [RelationshipsController::class, 'index'])->name('relationships.index');
        Route::get('/models/{modelId}/relationships', [RelationshipsController::class, 'create'])->name('relationships.create');
        Route::post('/relationships/{model}', [RelationshipsController::class, 'store'])->name('relationships.store');
        Route::post('/relationships/{relationship}/destroy', [RelationshipsController::class, 'destroy'])->name('relationships.destroy');


        /** TEST METHODS */
        Route::get('/journey', [JourneyController::class, 'index'])->name('journey.index');
        Route::get('/create-file', [JourneyController::class, 'createFile'])->name('create-file');

        Route::get('/generate', [JourneyController::class, 'generate'])->name('generate');
        Route::get('/generate/success', [JourneyController::class, 'successfullyGenerated'])->name('generate.success');

    });

