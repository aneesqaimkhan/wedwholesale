<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CreateTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {name} {subdomain} {database_name} {database_username} {database_password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant with subdomain and database credentials';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $subdomain = $this->argument('subdomain');
        $databaseName = $this->argument('database_name');
        $databaseUsername = $this->argument('database_username');
        $databasePassword = $this->argument('database_password');

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
            'database_username' => $databaseUsername,
            'database_password' => $databasePassword,
            'database_port' => 3306,
            'is_active' => true,
        ]);

        $this->info("Tenant '{$name}' created successfully!");
        $this->info("Subdomain: {$subdomain}.localhost");
        $this->info("Database: {$databaseName}");
        $this->info("Database User: {$databaseUsername}");

        return 0;
    }
}
