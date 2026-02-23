<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\SuperAdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (JWT Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| Super Admin Only (status = 3)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'superadmin'])
    ->prefix('superadmin')
    ->group(function () {

        Route::get('/users', [SuperAdminController::class, 'userList']);
        Route::get('/users/{id}', [SuperAdminController::class, 'viewUser']);
        Route::post('/users/{id}/status', [SuperAdminController::class, 'updateStatus']);
        Route::delete('/users/{id}', [SuperAdminController::class, 'deleteUser']);
});