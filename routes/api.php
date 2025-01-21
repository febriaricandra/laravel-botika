<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\JobController;
use App\Http\Controllers\Api\v1\DivisionController;
use App\Http\Controllers\Api\v1\EmployeeController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware([JWTMiddleware::class])->prefix('v1')->group(function () {
    Route::get('/user', function () {
        return Auth::user();
    });

    Route::apiResource('/jobs', JobController::class);
    Route::apiResource('/divisions', DivisionController::class);
    Route::apiResource('/employees', EmployeeController::class);

    Route::post('/refresh', [AuthController::class, 'refresh']);
});