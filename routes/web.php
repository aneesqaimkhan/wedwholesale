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


// Get domain configuration based on environment (safe for CLI)
$hostForEnvCheck = app()->runningInConsole() ? 'localhost' : request()->getHost();
$isLocal = str_contains($hostForEnvCheck, 'localhost') || str_contains($hostForEnvCheck, '127.0.0.1');
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
    
    // Database connection test route
    Route::get('/db-test', function (\Illuminate\Http\Request $request) {
        $subdomain = $request->route('subdomain');
        $tenant = $request->attributes->get('current_tenant');
        
        try {
            // Test tenant database connection
            $dbName = DB::select('SELECT DATABASE() as dbname')[0]->dbname;
            
            return response()->json([
                'success' => true,
                'subdomain' => $subdomain,
                'tenant_name' => $tenant->name ?? 'Unknown',
                'current_database' => $dbName,
                'connection_status' => 'Connected',
                'database_config' => [
                    'database' => config('database.connections.tenant.database'),
                    'host' => config('database.connections.tenant.host'),
                    'port' => config('database.connections.tenant.port'),
                    'username' => config('database.connections.tenant.username'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'subdomain' => $subdomain
            ], 500);
        }
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
        
        // Customer routes
        Route::resource('customers', App\Http\Controllers\Tenant\CustomerController::class)->names([
            'index' => 'customers.index',
            'create' => 'customers.create',
            'store' => 'customers.store',
            'show' => 'customers.show',
            'edit' => 'customers.edit',
            'update' => 'customers.update',
            'destroy' => 'customers.destroy',
        ]);
        
        // Salesman routes
        Route::resource('salesmen', App\Http\Controllers\Tenant\SalesmanController::class)->names([
            'index' => 'salesmen.index',
            'create' => 'salesmen.create',
            'store' => 'salesmen.store',
            'show' => 'salesmen.show',
            'edit' => 'salesmen.edit',
            'update' => 'salesmen.update',
            'destroy' => 'salesmen.destroy',
        ]);
        
        // Product routes
        Route::resource('products', App\Http\Controllers\Tenant\ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'show' => 'products.show',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);

        // Sales Invoices routes
        Route::resource('sales-invoices', App\Http\Controllers\Tenant\SalesInvoiceController::class)->parameters([
            'sales-invoices' => 'sales_invoice'
        ])->names([
            'index' => 'sales_invoices.index',
            'create' => 'sales_invoices.create',
            'store' => 'sales_invoices.store',
            'show' => 'sales_invoices.show',
            'edit' => 'sales_invoices.edit',
            'update' => 'sales_invoices.update',
            'destroy' => 'sales_invoices.destroy',
        ]);
    });
});
