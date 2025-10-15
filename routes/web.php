<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TenantAuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::get('/', [TenantAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [TenantAuthController::class, 'login']);
Route::post('/logout', [TenantAuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [TenantAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [TenantAuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [TenantAuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [TenantAuthController::class, 'resetPassword'])->name('password.update');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
