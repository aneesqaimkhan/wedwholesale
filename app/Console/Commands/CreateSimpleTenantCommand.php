<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CreateSimpleTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-simple {name} {subdomain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant with simple setup (uses same DB credentials)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $subdomain = $this->argument('subdomain');
        $databaseName = 'tenant_' . $subdomain . '_db';

        // Check if tenant already exists
        if (Tenant::where('subdomain', $subdomain)->exists()) {
            $this->error("Tenant with subdomain '{$subdomain}' already exists!");
            return 1;
        }

        // Create the tenant database
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            $this->info("Database '{$databaseName}' created successfully.");
        } catch (\Exception $e) {
            $this->error("Failed to create database: " . $e->getMessage());
            return 1;
        }

        // Create the tenant record in master database
        $tenant = Tenant::create([
            'name' => $name,
            'subdomain' => $subdomain,
            'database_name' => $databaseName,
            'database_host' => '127.0.0.1',
            'database_username' => env('DB_USERNAME', 'root'),
            'database_password' => env('DB_PASSWORD', ''),
            'database_port' => 3306,
            'is_active' => true,
        ]);

        $this->info("Tenant '{$name}' created successfully!");
        $this->info("Subdomain: {$subdomain}.localhost");
        $this->info("Database: {$databaseName}");
        $this->info("Database User: " . env('DB_USERNAME', 'root'));

        // Ask if user wants to run migrations
        if ($this->confirm('Do you want to run migrations for this tenant?')) {
            $this->call('tenant:migrate', ['subdomain' => $subdomain]);
        }

        return 0;
    }
}
