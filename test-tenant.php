<?php

/**
 * Test script to verify tenant functionality
 * Run this script from the Laravel root directory: php test-tenant.php
 */

require_once 'vendor/autoload.php';

use App\Models\Tenant;
use App\Models\Tenant\Product;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing tenant functionality...\n\n";

    // 1. Find the demo tenant
    $tenant = Tenant::findByDomain('demo');
    if (!$tenant) {
        echo "❌ Demo tenant not found. Please run create-tenant-example.php first.\n";
        exit(1);
    }

    echo "✓ Found tenant: {$tenant->name} (ID: {$tenant->id})\n";

    // 2. Configure tenant database connection
    $tenant->configureDatabaseConnection();
    echo "✓ Database connection configured\n";

    // 3. Test database connection
    $pdo = DB::connection('tenant')->getPdo();
    echo "✓ Database connection successful\n";

    // 4. Test tenant models
    $productCount = Product::count();
    $customerCount = Customer::count();
    $orderCount = Order::count();

    echo "✓ Tenant data found:\n";
    echo "  - Products: {$productCount}\n";
    echo "  - Customers: {$customerCount}\n";
    echo "  - Orders: {$orderCount}\n";

    // 5. Test creating a new product
    $newProduct = Product::create([
        'name' => 'Test Product',
        'description' => 'A test product created by the test script',
        'price' => 99.99,
        'stock_quantity' => 10,
        'sku' => 'TEST-' . time(),
        'category' => 'Test',
        'is_active' => true,
    ]);

    echo "✓ Created test product: {$newProduct->name} (ID: {$newProduct->id})\n";

    // 6. Test product relationships
    $product = Product::with('orderItems')->first();
    if ($product) {
        echo "✓ Product relationships working: {$product->name} has {$product->orderItems->count()} order items\n";
    }

    // 7. Test customer statistics
    $customer = Customer::first();
    if ($customer) {
        echo "✓ Customer statistics: {$customer->name} has {$customer->total_orders} orders, spent \${$customer->total_spent}\n";
    }

    // 8. Clean up test product
    $newProduct->delete();
    echo "✓ Test product cleaned up\n";

    echo "\n🎉 All tests passed! Tenant functionality is working correctly.\n";
    echo "\nYou can now:\n";
    echo "1. Access the API: https://demo.yourdomain.com/api/tenant/products\n";
    echo "2. Use the admin panel with: admin@demo.com / password123\n";
    echo "3. Create more tenants using the same process\n";

} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
