# Multi-Tenant Laravel Application Guide

This guide explains how to work with tenant databases in your Laravel application. The system supports multiple tenants, each with their own database, while maintaining a master database for tenant management.

## Architecture Overview

### Database Structure
- **Master Database**: Contains tenant information and master users
- **Tenant Databases**: Each tenant has its own isolated database
- **Dynamic Connection**: Database connections are switched at runtime based on tenant identification

### Key Components
1. **Tenant Model**: Manages tenant information and database configuration
2. **MasterUser Model**: Handles authentication across tenants
3. **Tenant Models**: Business logic models for each tenant
4. **Tenant Middleware**: Automatically switches database connections
5. **Artisan Commands**: Manage tenant migrations and seeding

## Getting Started

### 1. Database Setup

First, create your master database and run the master migrations:

```bash
# Run master database migrations
php artisan migrate --database=master

# Seed master database with sample data
php artisan db:seed --class=MasterDatabaseSeeder --database=master
```

### 2. Creating a New Tenant

To create a new tenant, you need to:

1. **Create the tenant record in the master database**
2. **Create the tenant's database**
3. **Run tenant migrations**
4. **Seed tenant data**

#### Example: Creating a Tenant Programmatically

```php
use App\Models\Tenant;

// Create tenant record
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
    'address' => '123 Demo Street, Demo City',
    'license_start_date' => now(),
    'license_end_date' => now()->addYear(),
    'is_active' => true,
]);

// Create the database
DB::statement("CREATE DATABASE IF NOT EXISTS wedwholesale_demo");

// Run migrations for this tenant
Artisan::call('tenant:migrate', ['tenant' => $tenant->id]);

// Seed the tenant database
Artisan::call('tenant:seed', ['tenant' => $tenant->id]);
```

### 3. Tenant Identification

The system supports multiple ways to identify tenants:

#### Method 1: Subdomain
- URL: `demo.yourdomain.com`
- The middleware extracts `demo` as the tenant identifier

#### Method 2: Header-based
```http
X-Tenant-ID: 1
# or
X-Tenant-Domain: demo
```

## Working with Tenant Models

### Creating Tenant Models

All tenant-specific models should:
1. Use the `tenant` connection
2. Be placed in the `App\Models\Tenant` namespace
3. Extend the base `Model` class

```php
<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'tenant';
    
    protected $fillable = [
        'name',
        'price',
        'description',
    ];
}
```

### Creating Migrations for Tenants

Tenant migrations should:
1. Be placed in `database/migrations/tenant/`
2. Use the `tenant` connection
3. Follow the same naming convention as regular migrations

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('products');
    }
};
```

## Artisan Commands

### Tenant Migration Commands

```bash
# Migrate all active tenants
php artisan tenant:migrate

# Migrate specific tenant by ID
php artisan tenant:migrate 1

# Migrate specific tenant by domain
php artisan tenant:migrate demo

# Fresh migration (drops all tables and re-runs migrations)
php artisan tenant:migrate --fresh

# Fresh migration with seeding
php artisan tenant:migrate --fresh --seed
```

### Tenant Seeding Commands

```bash
# Seed all active tenants
php artisan tenant:seed

# Seed specific tenant
php artisan tenant:seed 1

# Seed with specific seeder class
php artisan tenant:seed --class=TenantDatabaseSeeder
```

## API Routes

### Tenant Routes Structure

All tenant routes are prefixed with `/api/tenant/` and protected by the `tenant` middleware:

```php
// routes/tenant.php
Route::middleware(['tenant'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    
    // Custom routes
    Route::patch('products/{product}/stock', [ProductController::class, 'updateStock']);
    Route::get('customers/{customer}/statistics', [CustomerController::class, 'statistics']);
});
```

### Example API Usage

#### Using Subdomain
```bash
curl -X GET https://demo.yourdomain.com/api/tenant/products
```

#### Using Headers
```bash
curl -X GET https://yourdomain.com/api/tenant/products \
  -H "X-Tenant-Domain: demo"
```

## Controllers

### Tenant Controllers

Tenant controllers should:
1. Be placed in `App\Http\Controllers\Tenant`
2. Use tenant models
3. Handle tenant-specific business logic

```php
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    
    public function store(Request $request)
    {
        $product = Product::create($request->validated());
        
        return response()->json([
            'success' => true,
            'data' => $product
        ], 201);
    }
}
```

## Middleware

### Tenant Middleware

The `TenantMiddleware` automatically:
1. Identifies the tenant from the request
2. Validates tenant license
3. Configures the database connection
4. Makes tenant available in the request

```php
// Access tenant in controller
public function index(Request $request)
{
    $tenant = $request->get('tenant');
    // Use tenant information
}
```

## Best Practices

### 1. Database Isolation
- Each tenant has a completely separate database
- No shared data between tenants
- Use the `tenant` connection for all tenant models

### 2. Migration Management
- Keep tenant migrations in `database/migrations/tenant/`
- Use descriptive migration names
- Test migrations on all tenants before deployment

### 3. Model Organization
- Place tenant models in `App\Models\Tenant`
- Use the `tenant` connection
- Implement proper relationships within tenant scope

### 4. API Design
- Use consistent response formats
- Implement proper error handling
- Include tenant context in responses when needed

### 5. Security
- Validate tenant access in middleware
- Check license validity
- Implement proper authentication

## Troubleshooting

### Common Issues

#### 1. Database Connection Errors
```bash
# Check if tenant database exists
mysql -u root -p -e "SHOW DATABASES LIKE 'wedwholesale_demo';"

# Test connection manually
php artisan tinker
>>> $tenant = App\Models\Tenant::find(1);
>>> $tenant->configureDatabaseConnection();
>>> DB::connection('tenant')->getPdo();
```

#### 2. Migration Issues
```bash
# Check migration status
php artisan migrate:status --database=tenant

# Reset migrations
php artisan tenant:migrate --fresh
```

#### 3. Tenant Not Found
- Verify tenant exists in master database
- Check domain configuration
- Ensure middleware is properly registered

### Debugging

#### Enable Query Logging
```php
// In your controller or service
DB::connection('tenant')->enableQueryLog();
// ... your code ...
dd(DB::connection('tenant')->getQueryLog());
```

#### Check Current Connection
```php
// In tinker or controller
dd(DB::getDefaultConnection());
dd(Config::get('database.default'));
```

## Example: Complete Tenant Setup

Here's a complete example of setting up a new tenant:

```php
<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// 1. Create tenant record
$tenant = Tenant::create([
    'name' => 'New Company',
    'domain' => 'newcompany',
    'database_name' => 'wedwholesale_newcompany',
    'database_host' => '127.0.0.1',
    'database_port' => 3306,
    'database_username' => 'root',
    'database_password' => '',
    'company_name' => 'New Company Ltd',
    'contact_email' => 'admin@newcompany.com',
    'license_start_date' => now(),
    'license_end_date' => now()->addYear(),
    'is_active' => true,
]);

// 2. Create database
DB::statement("CREATE DATABASE IF NOT EXISTS wedwholesale_newcompany");

// 3. Run migrations
Artisan::call('tenant:migrate', ['tenant' => $tenant->id]);

// 4. Seed data
Artisan::call('tenant:seed', ['tenant' => $tenant->id]);

// 5. Create master user for this tenant
$masterUser = \App\Models\MasterUser::create([
    'name' => 'Admin User',
    'email' => 'admin@newcompany.com',
    'password' => bcrypt('password'),
    'tenant_id' => $tenant->id,
    'is_active' => true,
]);

echo "Tenant setup complete! Access via: https://newcompany.yourdomain.com";
```

This completes the multi-tenant setup. Each tenant now has their own isolated database with all the necessary tables and sample data.
