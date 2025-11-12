<?php

use Illuminate\Support\Facades\Route;
use Beartropy\Ui\Http\Controllers\AssetController;

Route::middleware('web')
    ->prefix('beartropy-ui-assets')
    ->group(function () {
        // --- Nueva ruta dedicada a Ziggy ---
        Route::get('ziggy.js', [AssetController::class, 'ziggy'])
            ->name('beartropy.assets.ziggy');

        // --- Ruta existente para assets comunes ---
        Route::get('{file}', [AssetController::class, 'beartropyAssets'])
            ->where('file', '.*');
    });
