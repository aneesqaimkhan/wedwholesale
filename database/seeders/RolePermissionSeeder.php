<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define all permissions based on modules
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'module' => 'dashboard', 'description' => 'Access to view dashboard'],
            
            // Customers
            ['name' => 'View Customers', 'slug' => 'customers.view', 'module' => 'customers', 'description' => 'View customer list'],
            ['name' => 'Create Customers', 'slug' => 'customers.create', 'module' => 'customers', 'description' => 'Create new customers'],
            ['name' => 'Edit Customers', 'slug' => 'customers.edit', 'module' => 'customers', 'description' => 'Edit existing customers'],
            ['name' => 'Delete Customers', 'slug' => 'customers.delete', 'module' => 'customers', 'description' => 'Delete customers'],
            
            // Suppliers
            ['name' => 'View Suppliers', 'slug' => 'suppliers.view', 'module' => 'suppliers', 'description' => 'View supplier list'],
            ['name' => 'Create Suppliers', 'slug' => 'suppliers.create', 'module' => 'suppliers', 'description' => 'Create new suppliers'],
            ['name' => 'Edit Suppliers', 'slug' => 'suppliers.edit', 'module' => 'suppliers', 'description' => 'Edit existing suppliers'],
            ['name' => 'Delete Suppliers', 'slug' => 'suppliers.delete', 'module' => 'suppliers', 'description' => 'Delete suppliers'],
            
            // Salesmen
            ['name' => 'View Salesmen', 'slug' => 'salesmen.view', 'module' => 'salesmen', 'description' => 'View salesman list'],
            ['name' => 'Create Salesmen', 'slug' => 'salesmen.create', 'module' => 'salesmen', 'description' => 'Create new salesmen'],
            ['name' => 'Edit Salesmen', 'slug' => 'salesmen.edit', 'module' => 'salesmen', 'description' => 'Edit existing salesmen'],
            ['name' => 'Delete Salesmen', 'slug' => 'salesmen.delete', 'module' => 'salesmen', 'description' => 'Delete salesmen'],
            
            // Products
            ['name' => 'View Products', 'slug' => 'products.view', 'module' => 'products', 'description' => 'View product list'],
            ['name' => 'Create Products', 'slug' => 'products.create', 'module' => 'products', 'description' => 'Create new products'],
            ['name' => 'Edit Products', 'slug' => 'products.edit', 'module' => 'products', 'description' => 'Edit existing products'],
            ['name' => 'Delete Products', 'slug' => 'products.delete', 'module' => 'products', 'description' => 'Delete products'],
            
            // Sales Invoices
            ['name' => 'View Sales Invoices', 'slug' => 'sales_invoices.view', 'module' => 'sales_invoices', 'description' => 'View sales invoice list'],
            ['name' => 'Create Sales Invoices', 'slug' => 'sales_invoices.create', 'module' => 'sales_invoices', 'description' => 'Create new sales invoices'],
            ['name' => 'Edit Sales Invoices', 'slug' => 'sales_invoices.edit', 'module' => 'sales_invoices', 'description' => 'Edit existing sales invoices'],
            ['name' => 'Delete Sales Invoices', 'slug' => 'sales_invoices.delete', 'module' => 'sales_invoices', 'description' => 'Delete sales invoices'],
            
            // Companies
            ['name' => 'View Companies', 'slug' => 'companies.view', 'module' => 'companies', 'description' => 'View company list'],
            ['name' => 'Create Companies', 'slug' => 'companies.create', 'module' => 'companies', 'description' => 'Create new companies'],
            ['name' => 'Edit Companies', 'slug' => 'companies.edit', 'module' => 'companies', 'description' => 'Edit existing companies'],
            ['name' => 'Delete Companies', 'slug' => 'companies.delete', 'module' => 'companies', 'description' => 'Delete companies'],
            
            // Purchases
            ['name' => 'View Purchases', 'slug' => 'purchases.view', 'module' => 'purchases', 'description' => 'View purchase list'],
            ['name' => 'Create Purchases', 'slug' => 'purchases.create', 'module' => 'purchases', 'description' => 'Create new purchases'],
            ['name' => 'Edit Purchases', 'slug' => 'purchases.edit', 'module' => 'purchases', 'description' => 'Edit existing purchases'],
            ['name' => 'Delete Purchases', 'slug' => 'purchases.delete', 'module' => 'purchases', 'description' => 'Delete purchases'],
            
            // Receipt Payments
            ['name' => 'View Receipt Payments', 'slug' => 'receipt_payments.view', 'module' => 'receipt_payments', 'description' => 'View receipt payment list'],
            ['name' => 'Create Receipt Payments', 'slug' => 'receipt_payments.create', 'module' => 'receipt_payments', 'description' => 'Create new receipt payments'],
            ['name' => 'Edit Receipt Payments', 'slug' => 'receipt_payments.edit', 'module' => 'receipt_payments', 'description' => 'Edit existing receipt payments'],
            ['name' => 'Delete Receipt Payments', 'slug' => 'receipt_payments.delete', 'module' => 'receipt_payments', 'description' => 'Delete receipt payments'],
            
            // Areas
            ['name' => 'View Areas', 'slug' => 'areas.view', 'module' => 'areas', 'description' => 'View area list'],
            ['name' => 'Create Areas', 'slug' => 'areas.create', 'module' => 'areas', 'description' => 'Create new areas'],
            ['name' => 'Edit Areas', 'slug' => 'areas.edit', 'module' => 'areas', 'description' => 'Edit existing areas'],
            ['name' => 'Delete Areas', 'slug' => 'areas.delete', 'module' => 'areas', 'description' => 'Delete areas'],
            
            // Expense Types
            ['name' => 'View Expense Types', 'slug' => 'expense_types.view', 'module' => 'expense_types', 'description' => 'View expense type list'],
            ['name' => 'Create Expense Types', 'slug' => 'expense_types.create', 'module' => 'expense_types', 'description' => 'Create new expense types'],
            ['name' => 'Edit Expense Types', 'slug' => 'expense_types.edit', 'module' => 'expense_types', 'description' => 'Edit existing expense types'],
            ['name' => 'Delete Expense Types', 'slug' => 'expense_types.delete', 'module' => 'expense_types', 'description' => 'Delete expense types'],
            
            // Expenses
            ['name' => 'View Expenses', 'slug' => 'expenses.view', 'module' => 'expenses', 'description' => 'View expense list'],
            ['name' => 'Create Expenses', 'slug' => 'expenses.create', 'module' => 'expenses', 'description' => 'Create new expenses'],
            ['name' => 'Edit Expenses', 'slug' => 'expenses.edit', 'module' => 'expenses', 'description' => 'Edit existing expenses'],
            ['name' => 'Delete Expenses', 'slug' => 'expenses.delete', 'module' => 'expenses', 'description' => 'Delete expenses'],
            
            // List Status Manual
            ['name' => 'View List Status Manual', 'slug' => 'list_status_manual.view', 'module' => 'list_status_manual', 'description' => 'View list status manual'],
            
            // Users (User Management)
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users', 'description' => 'View user list'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'users', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users', 'description' => 'Delete users'],
            ['name' => 'Manage Roles', 'slug' => 'users.manage_roles', 'module' => 'users', 'description' => 'Assign and manage user roles'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Define roles with their permissions
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
                'permissions' => array_column($permissions, 'slug'), // All permissions
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to manage all modules',
                'is_active' => true,
                'permissions' => array_values(array_filter(array_column($permissions, 'slug'), function($slug) {
                    return $slug !== 'users.manage_roles'; // Admins can do everything except manage roles
                })),
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager level access to view and manage operations',
                'is_active' => true,
                'permissions' => [
                    'dashboard.view',
                    'customers.view', 'customers.create', 'customers.edit',
                    'suppliers.view', 'suppliers.create', 'suppliers.edit',
                    'salesmen.view', 'salesmen.create', 'salesmen.edit',
                    'products.view', 'products.create', 'products.edit',
                    'sales_invoices.view', 'sales_invoices.create', 'sales_invoices.edit',
                    'companies.view', 'companies.create', 'companies.edit',
                    'purchases.view', 'purchases.create', 'purchases.edit',
                    'receipt_payments.view', 'receipt_payments.create', 'receipt_payments.edit',
                    'areas.view', 'areas.create', 'areas.edit',
                    'expense_types.view', 'expense_types.create', 'expense_types.edit',
                    'expenses.view', 'expenses.create', 'expenses.edit',
                    'list_status_manual.view',
                ],
            ],
            [
                'name' => 'Sales Manager',
                'slug' => 'sales_manager',
                'description' => 'Access to sales-related modules',
                'is_active' => true,
                'permissions' => [
                    'dashboard.view',
                    'customers.view', 'customers.create', 'customers.edit',
                    'salesmen.view', 'salesmen.create', 'salesmen.edit',
                    'products.view',
                    'sales_invoices.view', 'sales_invoices.create', 'sales_invoices.edit',
                    'receipt_payments.view', 'receipt_payments.create', 'receipt_payments.edit',
                    'areas.view',
                    'list_status_manual.view',
                ],
            ],
            [
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => 'Access to financial and accounting modules',
                'is_active' => true,
                'permissions' => [
                    'dashboard.view',
                    'customers.view',
                    'suppliers.view',
                    'sales_invoices.view',
                    'receipt_payments.view', 'receipt_payments.create', 'receipt_payments.edit',
                    'expense_types.view', 'expense_types.create', 'expense_types.edit',
                    'expenses.view', 'expenses.create', 'expenses.edit',
                ],
            ],
            [
                'name' => 'Salesperson',
                'slug' => 'salesperson',
                'description' => 'Access to create and manage sales',
                'is_active' => true,
                'permissions' => [
                    'dashboard.view',
                    'customers.view',
                    'products.view',
                    'sales_invoices.view', 'sales_invoices.create', 'sales_invoices.edit',
                    'list_status_manual.view',
                ],
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to view reports and data',
                'is_active' => true,
                'permissions' => [
                    'dashboard.view',
                    'customers.view',
                    'suppliers.view',
                    'salesmen.view',
                    'products.view',
                    'sales_invoices.view',
                    'companies.view',
                    'purchases.view',
                    'receipt_payments.view',
                    'areas.view',
                    'expense_types.view',
                    'expenses.view',
                    'list_status_manual.view',
                ],
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
            
            // Assign permissions to role
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
