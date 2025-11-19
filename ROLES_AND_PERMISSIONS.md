# Roles & Permissions System

## Overview

A comprehensive role-based access control (RBAC) system has been implemented for your Laravel application. This system allows you to assign roles to users and control access to different modules and features based on permissions.

## Database Structure

The system uses the following tables:
- `roles` - Stores role definitions
- `permissions` - Stores permission definitions
- `role_permission` - Pivot table linking roles to permissions
- `user_role` - Pivot table linking users to roles

## Predefined Roles

The system comes with the following predefined roles:

### 1. Super Admin
- **Slug**: `super_admin`
- **Description**: Full system access with all permissions
- **Permissions**: All permissions including role management

### 2. Admin
- **Slug**: `admin`
- **Description**: Administrative access to manage all modules
- **Permissions**: All permissions except role management

### 3. Manager
- **Slug**: `manager`
- **Description**: Manager level access to view and manage operations
- **Permissions**: View, create, and edit access to most modules (no delete access)

### 4. Sales Manager
- **Slug**: `sales_manager`
- **Description**: Access to sales-related modules
- **Permissions**: Customers, salesmen, products, sales invoices, receipt payments, areas

### 5. Accountant
- **Slug**: `accountant`
- **Description**: Access to financial and accounting modules
- **Permissions**: Customers, suppliers, sales invoices, receipt payments, expenses

### 6. Salesperson
- **Slug**: `salesperson`
- **Description**: Access to create and manage sales
- **Permissions**: View customers and products, create/edit sales invoices

### 7. Viewer
- **Slug**: `viewer`
- **Description**: Read-only access to view reports and data
- **Permissions**: View access to all modules (no create, edit, or delete)

## Permissions

Each module has the following permission types:
- `view` - View/list items
- `create` - Create new items
- `edit` - Edit existing items
- `delete` - Delete items

### Available Modules & Permissions

1. **Dashboard**: `dashboard.view`
2. **Customers**: `customers.view`, `customers.create`, `customers.edit`, `customers.delete`
3. **Suppliers**: `suppliers.view`, `suppliers.create`, `suppliers.edit`, `suppliers.delete`
4. **Salesmen**: `salesmen.view`, `salesmen.create`, `salesmen.edit`, `salesmen.delete`
5. **Products**: `products.view`, `products.create`, `products.edit`, `products.delete`
6. **Sales Invoices**: `sales_invoices.view`, `sales_invoices.create`, `sales_invoices.edit`, `sales_invoices.delete`
7. **Companies**: `companies.view`, `companies.create`, `companies.edit`, `companies.delete`
8. **Purchases**: `purchases.view`, `purchases.create`, `purchases.edit`, `purchases.delete`
9. **Receipt Payments**: `receipt_payments.view`, `receipt_payments.create`, `receipt_payments.edit`, `receipt_payments.delete`
10. **Areas**: `areas.view`, `areas.create`, `areas.edit`, `areas.delete`
11. **Expense Types**: `expense_types.view`, `expense_types.create`, `expense_types.edit`, `expense_types.delete`
12. **Expenses**: `expenses.view`, `expenses.create`, `expenses.edit`, `expenses.delete`
13. **List Status Manual**: `list_status_manual.view`
14. **Users**: `users.view`, `users.create`, `users.edit`, `users.delete`, `users.manage_roles`

## Setup Instructions

### 1. Run Migrations

Run the migrations to create the necessary tables:

```bash
php artisan migrate
```

For multi-tenant setup, run migrations for each tenant:

```bash
php artisan tenant:migrate {subdomain}
```

### 2. Seed Roles and Permissions

Run the seeder to populate roles and permissions:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

For multi-tenant setup, you'll need to seed each tenant database. You can create a command or run it manually for each tenant.

### 3. Assign Roles to Users

You can assign roles to users programmatically:

```php
use App\Models\User;
use App\Models\Role;

// Get a user
$user = User::find(1);

// Assign a role by slug
$user->assignRoles(['admin']);

// Assign multiple roles
$user->assignRoles(['manager', 'sales_manager']);
```

## Usage Examples

### In Controllers

#### Check Permission in Controller Method

```php
public function index()
{
    if (!auth()->user()->hasPermission('customers.view')) {
        abort(403, 'You do not have permission to view customers.');
    }
    
    // Your code here
}
```

#### Using Middleware in Routes

```php
Route::middleware(['auth', 'permission:customers.view'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
});

Route::middleware(['auth', 'permission:customers.create'])->group(function () {
    Route::post('/customers', [CustomerController::class, 'store']);
});
```

### In Blade Templates

#### Using Blade Directives

```blade
@permission('customers.create')
    <a href="{{ route('customers.create') }}" class="btn btn-primary">Create Customer</a>
@endpermission

@role('admin')
    <p>You are an administrator</p>
@endrole

@role(['admin', 'manager'])
    <p>You are an admin or manager</p>
@endrole

@anyPermission(['customers.create', 'customers.edit'])
    <p>You can create or edit customers</p>
@endanyPermission
```

#### Using Helper Functions

```blade
@if(user_has_permission('customers.delete'))
    <button class="btn btn-danger">Delete</button>
@endif

@if(user_has_role('admin'))
    <p>Admin content</p>
@endif

@if(user_has_any_permission(['customers.create', 'customers.edit']))
    <p>You can modify customers</p>
@endif
```

### In PHP Code

```php
// Check if user has permission
if (auth()->user()->hasPermission('customers.create')) {
    // Allow creating customer
}

// Check if user has role
if (auth()->user()->hasRole('admin')) {
    // Admin only code
}

// Check if user has any of multiple roles
if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
    // Admin or manager code
}

// Check if user has any of multiple permissions
if (auth()->user()->hasAnyPermission(['customers.create', 'customers.edit'])) {
    // User can create or edit customers
}

// Get all user permissions
$permissions = auth()->user()->permissions();

// Get all user roles
$roles = auth()->user()->roles;
```

## Managing Roles and Permissions

### Create a New Role

```php
use App\Models\Role;

$role = Role::create([
    'name' => 'Custom Role',
    'slug' => 'custom_role',
    'description' => 'Description of the custom role',
    'is_active' => true,
]);

// Assign permissions to role
$role->assignPermissions([
    'customers.view',
    'customers.create',
    'products.view',
]);
```

### Create a New Permission

```php
use App\Models\Permission;

$permission = Permission::create([
    'name' => 'View Reports',
    'slug' => 'reports.view',
    'module' => 'reports',
    'description' => 'Access to view reports',
]);
```

### Assign Permissions to Role

```php
$role = Role::where('slug', 'manager')->first();
$role->assignPermissions([
    'customers.view',
    'customers.create',
    'customers.edit',
]);
```

### Check Role Permissions

```php
$role = Role::where('slug', 'admin')->first();

if ($role->hasPermission('customers.create')) {
    // Role has permission
}
```

## Backward Compatibility

The system maintains backward compatibility with the existing `role` column in the `users` table. The following methods still work:

```php
// These methods check both the new role system and the old role column
$user->isAdmin();    // Checks if user has 'admin' role or role column is 'admin'
$user->isManager();  // Checks if user has 'manager' role or role column is 'manager'
```

## Best Practices

1. **Use Permissions, Not Roles**: Check for specific permissions rather than roles when possible. This provides more flexibility.

2. **Protect Routes**: Use middleware to protect routes that require specific permissions.

3. **Hide UI Elements**: Use Blade directives to hide UI elements that users don't have permission to access.

4. **Consistent Naming**: Follow the naming convention: `{module}.{action}` (e.g., `customers.create`, `products.edit`).

5. **Document Permissions**: Document which permissions are required for each feature.

## Troubleshooting

### Permission Not Working

1. Ensure the user has been assigned a role
2. Ensure the role has the required permission
3. Check that the permission slug matches exactly (case-sensitive)
4. Clear cache: `php artisan cache:clear`

### Seeder Not Running

1. Ensure migrations have been run
2. Check that the seeder class name is correct
3. For multi-tenant, ensure you're running the seeder on the correct database

### Middleware Not Working

1. Ensure the middleware is registered in `app/Http/Kernel.php`
2. Check that the route is using the middleware correctly
3. Verify the permission slug is correct

## Migration from Old Role System

If you're migrating from the old enum-based role system:

1. Run migrations to create new tables
2. Run the seeder to create roles and permissions
3. Migrate existing users to the new system:

```php
use App\Models\User;
use App\Models\Role;

// Migrate users based on their old role column
User::where('role', 'admin')->get()->each(function ($user) {
    $user->assignRoles(['admin']);
});

User::where('role', 'manager')->get()->each(function ($user) {
    $user->assignRoles(['manager']);
});

User::where('role', 'user')->get()->each(function ($user) {
    $user->assignRoles(['viewer']); // or assign appropriate role
});
```

4. Update your code to use the new permission system
5. Optionally remove the old `role` column after migration is complete



