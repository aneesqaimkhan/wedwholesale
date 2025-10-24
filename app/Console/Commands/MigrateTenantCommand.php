<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MigrateTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {subdomain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for a specific tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subdomain = $this->argument('subdomain');
        
        // Find tenant in master database
        $tenant = Tenant::where('subdomain', $subdomain)->first();
        
        if (!$tenant) {
            $this->error("Tenant with subdomain '{$subdomain}' not found!");
            return 1;
        }
        
        $this->info("Found tenant: {$tenant->name}");
        $this->info("Switching to database: {$tenant->database_name}");
        
        // Switch to tenant database
        $this->switchToTenantDatabase($tenant);
        
        // Run migrations
        $this->info("Running migrations...");
        $this->call('migrate');
        
        $this->info("Migrations completed for tenant: {$tenant->name}");
        
        return 0;
    }
    
    /**
     * Switch database connection to tenant's database
     */
    private function switchToTenantDatabase(Tenant $tenant)
    {
        // Set tenant database configuration
        Config::set('database.connections.tenant.database', $tenant->database_name);
        Config::set('database.connections.tenant.host', $tenant->database_host);
        Config::set('database.connections.tenant.username', $tenant->database_username);
        Config::set('database.connections.tenant.password', $tenant->database_password);
        Config::set('database.connections.tenant.port', $tenant->database_port);
        
        // Set default connection to tenant
        Config::set('database.default', 'tenant');
        
        // Purge and reconnect
        DB::purge('tenant');
        DB::reconnect('tenant');
    }
}
