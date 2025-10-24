<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Main application routes (master database)
Route::get('/', function () {
    return view('welcome');
});

// Tenant-specific routes (subdomain routes)
Route::domain('{subdomain}.localhost')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', function ($subdomain, \Illuminate\Http\Request $request) {
        // Check if user is already authenticated
        if (auth()->check()) {
            return redirect(url('/dashboard'));
        }
        
        // Redirect to login page if not authenticated
        return redirect(url('/login'));
    });
    
    // Test route (remove in production)
    Route::get('/test', function ($subdomain, \Illuminate\Http\Request $request) {
        return view('tenant.test');
    });
    
    // Debug route (remove in production)
    Route::get('/debug', function ($subdomain, \Illuminate\Http\Request $request) {
        return view('tenant.debug');
    });
    
    // Simple test route
    Route::get('/url-test', function ($subdomain, \Illuminate\Http\Request $request) {
        return response()->json([
            'subdomain' => $subdomain,
            'app_url' => config('app.url'),
            'login_url' => route('tenant.login', ['subdomain' => $subdomain]),
            'register_url' => route('tenant.register', ['subdomain' => $subdomain]),
            'simple_login' => url('/login'),
            'simple_register' => url('/register'),
        ]);
    });
    
    // Authentication routes
    Route::get('/login', [App\Http\Controllers\Tenant\AuthController::class, 'showLogin'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Tenant\AuthController::class, 'login']);
    
    Route::get('/register', [App\Http\Controllers\Tenant\AuthController::class, 'showRegister'])->name('tenant.register');
    Route::post('/register', [App\Http\Controllers\Tenant\AuthController::class, 'register']);
    
    Route::post('/logout', [App\Http\Controllers\Tenant\AuthController::class, 'logout'])->name('tenant.logout');
    
    // Protected routes (authentication required)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Tenant\AuthController::class, 'dashboard'])->name('tenant.dashboard');
    });
});
