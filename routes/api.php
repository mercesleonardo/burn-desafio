<?php

use App\Http\Controllers\{CompanyController, PositionController, UserController};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('users/search', [UserController::class, 'search']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::post('positions/{position}/apply', [PositionController::class, 'apply']);
    Route::apiResource('positions', PositionController::class);
});
