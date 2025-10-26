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


// Get domain configuration based on environment
$isLocal = str_contains(request()->getHost(), 'localhost') || str_contains(request()->getHost(), '127.0.0.1');
$deploymentConfig = config('deployment');

$domainPattern = $isLocal ? $deploymentConfig['local']['subdomain_pattern'] : $deploymentConfig['live']['subdomain_pattern'];


// dd($domainPattern);
// Tenant-specific routes (subdomain routes)
Route::domain($domainPattern)->group(function () {
    // Public routes (no authentication required)
    Route::get('/', function (\Illuminate\Http\Request $request) {
        $subdomain = $request->route('subdomain');
        
        // Check if user is already authenticated
        if (auth()->check()) {
            return redirect(url('/dashboard'));
        }
        
        // Redirect to login page if not authenticated
        return redirect(url('/login'));
    });
    
    // Test route (remove in production)
    Route::get('/test', function (\Illuminate\Http\Request $request) {
        $subdomain = $request->route('subdomain');
        return view('tenant.test');
    });
    
    // Debug route (remove in production)
    Route::get('/debug', function (\Illuminate\Http\Request $request) {
        $subdomain = $request->route('subdomain');
        return view('tenant.debug');
    });
    
    // Simple test route
    Route::get('/url-test', function (\Illuminate\Http\Request $request) {
        $subdomain = $request->route('subdomain');
        return response()->json([
            'subdomain' => $subdomain,
            'app_url' => config('app.url'),
            'host' => $request->getHost(),
            'domain_pattern' => $domainPattern,
            'is_local' => $isLocal,
            'login_url' => route('tenant.login', ['subdomain' => $subdomain]),
            'register_url' => route('tenant.register', ['subdomain' => $subdomain]),
            'simple_login' => url('/login'),
            'simple_register' => url('/register'),
            'deployment_config' => config('deployment'),
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
