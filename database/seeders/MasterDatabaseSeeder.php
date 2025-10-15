<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\MasterUser;
use Illuminate\Support\Facades\Hash;

class MasterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample tenant
        $tenant = Tenant::create([
            'name' => 'demo',
            'domain' => 'demo',
            'database_name' => 'medwholesale_demo',
            'database_host' => '127.0.0.1',
            'database_port' => 3306,
            'database_username' => 'root',
            'database_password' => '',
            'company_name' => 'Demo Medical Wholesale',
            'contact_email' => 'admin@demomedical.com',
            'contact_phone' => '+1-555-0123',
            'address' => '123 Medical Street, Health City, HC 12345',
            'logo' => null,
            'license_start_date' => now()->startOfYear(),
            'license_end_date' => now()->endOfYear()->addYear(),
            'is_active' => true,
            'settings' => [
                'timezone' => 'America/New_York',
                'currency' => 'USD',
                'date_format' => 'Y-m-d',
            ],
        ]);

        // Create sample master user
        MasterUser::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demomedical.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        // Create another sample tenant
        $tenant2 = Tenant::create([
            'name' => 'test',
            'domain' => 'test',
            'database_name' => 'medwholesale_test',
            'database_host' => '127.0.0.1',
            'database_port' => 3306,
            'database_username' => 'root',
            'database_password' => '',
            'company_name' => 'Test Medical Supplies',
            'contact_email' => 'admin@testmedical.com',
            'contact_phone' => '+1-555-0456',
            'address' => '456 Test Avenue, Medical Town, MT 67890',
            'logo' => null,
            'license_start_date' => now()->startOfYear(),
            'license_end_date' => now()->endOfYear()->addYear(),
            'is_active' => true,
            'settings' => [
                'timezone' => 'America/Chicago',
                'currency' => 'USD',
                'date_format' => 'm/d/Y',
            ],
        ]);

        // Create sample master user for second tenant
        MasterUser::create([
            'name' => 'Test Manager',
            'email' => 'admin@testmedical.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant2->id,
            'is_active' => true,
        ]);
    }
}
