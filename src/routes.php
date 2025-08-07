<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix('beartropy-ui-assets')
    ->group(function () {
        Route::get('{file}', [\Beartropy\Ui\Http\Controllers\AssetController::class, 'beartropyAssets'])
            ->where('file', '.*');
    });
