<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CreateTeamController;
use App\Http\Controllers\JoinTeamController;
use App\Http\Controllers\CreateNoteController;


// register,login and logout routes
Route::get('/register', [AuthController::class, 'showregister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showlogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot & reset Password routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teams', [CreateTeamController::class, 'createTeam']);
    Route::post('/teams/join', [JoinTeamController::class, 'joinTeam']);
    Route::post('/notes', [CreateNoteController::class, 'createNote']);
    Route::post('/teams', [CreateTeamController::class, 'createTeam']);
    Route::post('/teams/join', [JoinTeamController::class, 'joinTeam']);
    Route::post('/notes', [CreateNoteController::class, 'createNote']);
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
