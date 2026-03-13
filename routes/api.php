<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\SubscriptionController;

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

Route::middleware('auth:api')->group(function () {

    Route::get('/superadmin/users', [SuperAdminController::class, 'allUsers']);
    Route::post('/superadmin/block/{id}', [SuperAdminController::class, 'blockUser']);
    Route::post('/superadmin/unblock/{id}', [SuperAdminController::class, 'unblockUser']);

    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
});