<?php

use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'login']);

    Route::group([
        'middleware' => [
            'cookie.to.token',
            'auth:sanctum'
        ],
    ], function ($router) {

        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [UserController::class, 'getUser']);

        Route::put('users/change-active/{id}', [UserController::class, 'changeActive'])->name('users.change-active');
        Route::apiResource('users', UserController::class);

        /*
        |--------------------------------------------------------------------------
        | Gym System Routes
        |--------------------------------------------------------------------------
        */
        Route::apiResource('tenants', TenantController::class);
    });
});
