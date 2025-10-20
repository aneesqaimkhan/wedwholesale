<?php

/**
 * Example script to create a new tenant
 * Run this script from the Laravel root directory: php create-tenant-example.php
 */

require_once 'vendor/autoload.php';

use App\Models\Tenant;
use App\Models\MasterUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Creating new tenant...\n";

    // 1. Create tenant record
    $tenant = Tenant::create([
        'name' => 'Demo Company',
        'domain' => 'demo',
        'database_name' => 'wedwholesale_demo',
        'database_host' => '127.0.0.1',
        'database_port' => 3306,
        'database_username' => 'root',
        'database_password' => '',
        'company_name' => 'Demo Company Ltd',
        'contact_email' => 'admin@demo.com',
        'contact_phone' => '+1-555-0123',
        'address' => '123 Demo Street, Demo City, DC 12345',
        'license_start_date' => now(),
        'license_end_date' => now()->addYear(),
        'is_active' => true,
    ]);

    echo "✓ Tenant record created with ID: {$tenant->id}\n";

    // 2. Create the database
    DB::statement("CREATE DATABASE IF NOT EXISTS wedwholesale_demo");
    echo "✓ Database 'wedwholesale_demo' created\n";

    // 3. Run migrations for this tenant
    Artisan::call('tenant:migrate', ['tenant' => $tenant->id]);
    echo "✓ Migrations completed\n";

    // 4. Seed the tenant database
    Artisan::call('tenant:seed', ['tenant' => $tenant->id]);
    echo "✓ Database seeded with sample data\n";

    // 5. Create master user for this tenant
    $masterUser = MasterUser::create([
        'name' => 'Demo Admin',
        'email' => 'admin@demo.com',
        'password' => bcrypt('password123'),
        'tenant_id' => $tenant->id,
        'is_active' => true,
    ]);

    echo "✓ Master user created: admin@demo.com (password: password123)\n";

    echo "\n🎉 Tenant setup complete!\n";
    echo "Tenant ID: {$tenant->id}\n";
    echo "Domain: {$tenant->domain}\n";
    echo "Database: {$tenant->database_name}\n";
    echo "Access URL: https://demo.yourdomain.com/api/tenant/products\n";
    echo "Admin Login: admin@demo.com / password123\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
