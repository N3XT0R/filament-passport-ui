<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')
    ->prefix('api')
    ->name('api.')
    ->group(function () {
        Route::get('/', static function () {
            return response()->json(['status' => 'API is working']);
        });
    });
