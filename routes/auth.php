<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes de confirmation de mot de passe (si nécessaire)
Route::get('/confirm-password', [LoginController::class, 'showConfirmPasswordForm'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [LoginController::class, 'confirmPassword'])
    ->middleware('auth')
    ->name('password.confirm.submit');

// Routes de vérification d'email (si nécessaire)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [LoginController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [LoginController::class, 'resendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
