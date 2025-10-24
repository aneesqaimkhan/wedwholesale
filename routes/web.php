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
    Route::get('/', function ($subdomain, \Illuminate\Http\Request $request) {
        $tenant = $request->attributes->get('current_tenant');
        
        return response()->json([
            'message' => 'Welcome to tenant: ' . $tenant->name,
            'subdomain' => $tenant->subdomain,
            'database' => $tenant->database_name,
            'tenant_id' => $tenant->id,
        ]);
    });
    
    Route::get('/dashboard', function ($subdomain, \Illuminate\Http\Request $request) {
        $tenant = $request->attributes->get('current_tenant');
        
        return response()->json([
            'message' => 'Dashboard for tenant: ' . $tenant->name,
            'data' => 'This is tenant-specific data from database: ' . $tenant->database_name,
        ]);
    });
});
