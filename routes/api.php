<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserStatusController;
use Illuminate\Support\Facades\Broadcast;

// CSRF Cookie
Route::post('/sanctum/csrf-cookie', fn () => response()->noContent());

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user-online', [UserStatusController::class, 'updateOnlineStatus']);
});

// Broadcast Routes (auto-includes CORS)
Broadcast::routes(['middleware' => 'auth:sanctum']);