<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CustomerController;

/*
|--------------------------------------------------------------------------
| Tenant API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register tenant-specific API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['tenant'])->group(function () {
    
    // Product routes
    Route::apiResource('products', ProductController::class);
    Route::patch('products/{product}/stock', [ProductController::class, 'updateStock']);
    
    // Customer routes
    Route::apiResource('customers', CustomerController::class);
    Route::get('customers/{customer}/statistics', [CustomerController::class, 'statistics']);
    
    // Dashboard/Statistics routes
    Route::get('dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => \App\Models\Tenant\Product::count(),
                'total_customers' => \App\Models\Tenant\Customer::count(),
                'total_orders' => \App\Models\Tenant\Order::count(),
                'total_revenue' => \App\Models\Tenant\Order::sum('total_amount'),
            ]
        ]);
    });
    
});
