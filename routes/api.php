<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PositionController;

Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::get('users/search', [UserController::class, 'search']);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('positions', PositionController::class);
    Route::post('positions/{position}/apply', [PositionController::class, 'apply']);
});
