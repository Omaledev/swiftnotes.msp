<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CreateTeamController;
use App\Http\Controllers\JoinTeamController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;


// register,login and logout routes
Route::get('/register', [AuthController::class, 'showregister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showlogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot & reset Password routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/teams/create', [CreateTeamController::class, 'createTeam'])->name('teams.create');
    Route::post('/teams', [CreateTeamController::class, 'createTeams'])->name('teams.store');

    Route::get('/teams/join', [JoinTeamController::class, 'joinTeam'])->name('teams.join');
    Route::post('/teams/join', [JoinTeamController::class, 'joinTeams'])->name('teams.join.store');

    // Notes routes
    Route::get('/teams/{team}/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/teams/{team}/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Live editing tracking
    Route::post('/notes/{note}/start-editing', [NoteController::class, 'startEditing']);
    Route::post('/notes/{note}/stop-editing', [NoteController::class, 'stopEditing']);

    // Navigation Features
    // Route::get('/chat', [ChatController::class, 'index'])->name('pages.chat');
    // Route::get('/contacts', [ContactController::class, 'index'])->name('pages.contact');
    // Route::get('/profile', [ProfileController::class, 'show'])->name('pages.profile');
    // Route::get('/settings', [SettingsController::class, 'edit'])->name('pages.settings');
    // Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

});





