<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CreateTeamController;
use App\Http\Controllers\JoinTeamController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\DeleteTeamController;
use App\Http\Controllers\ShowMembersController;
use App\Http\Controllers\CollaboratorsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ChatController;


Route::get('/', function () {
    return redirect()->route('auth.login');
});
// register,login and logout routes
Route::get('/register', [AuthController::class, 'showregister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showlogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

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
    Route::delete('/teams/{team}', [DeleteTeamController::class, 'destroy'])->name('teams.destroy');

    Route::get('/teams/{team}/members', [ShowMembersController::class, 'showMembers'])->name('teams.members');
    Route::delete('/teams/{team}/members/{user}', [ShowMembersController::class, 'removeMember'])->name('teams.members.remove');

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
     // Settings Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('pages.settings');
        Route::post('/name', [SettingsController::class, 'updateName'])->name('settings.name.update');
        Route::post('/email', [SettingsController::class, 'updateEmail'])->name('settings.email.update');
        Route::get('/email/verify/{token}', [SettingsController::class, 'verifyEmailChange'])
         ->name('email.change.verify');
        Route::post('/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
        Route::put('/teams/{team}', [SettingsController::class, 'updateTeamName'])->name('settings.team.update');
        Route::post('/account/delete', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
        Route::delete('/settings/teams/{team}', [SettingsController::class, 'deleteTeam'])->name('settings.team.delete');
        Route::post('/settings/teams/{team}/leave', [SettingsController::class, 'leaveTeam'])->name('settings.team.leave');
    });

    Route::get('/collaborators', [CollaboratorsController::class, 'index'])
       ->name('pages.collaborators');
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Route::get('/chat', [ChatController::class, 'index'])->name('pages.chat');
     // Route::prefix('messages')->group(function () {
    // Route::get('/create/{recipient}', [MessageController::class, 'create'])
    //     ->name('messages.create');
    // Route::post('/', [MessageController::class, 'store'])
    //     ->name('messages.store');
    //  });

});







