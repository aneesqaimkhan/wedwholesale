<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate 
                            {tenant? : The tenant ID or domain to migrate}
                            {--fresh : Drop all tables and re-run all migrations}
                            {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantIdentifier = $this->argument('tenant');
        $fresh = $this->option('fresh');
        $seed = $this->option('seed');

        if ($tenantIdentifier) {
            // Migrate specific tenant
            $tenant = $this->findTenant($tenantIdentifier);
            if (!$tenant) {
                $this->error("Tenant not found: {$tenantIdentifier}");
                return 1;
            }

            $this->migrateTenant($tenant, $fresh, $seed);
        } else {
            // Migrate all tenants
            $tenants = Tenant::where('is_active', true)->get();
            
            if ($tenants->isEmpty()) {
                $this->info('No active tenants found.');
                return 0;
            }

            $this->info("Found {$tenants->count()} active tenants.");

            foreach ($tenants as $tenant) {
                $this->migrateTenant($tenant, $fresh, $seed);
            }
        }

        return 0;
    }

    /**
     * Find tenant by ID or domain
     */
    private function findTenant(string $identifier): ?Tenant
    {
        // Try to find by ID first
        if (is_numeric($identifier)) {
            return Tenant::find($identifier);
        }

        // Try to find by domain
        return Tenant::findByDomain($identifier);
    }

    /**
     * Migrate a specific tenant
     */
    private function migrateTenant(Tenant $tenant, bool $fresh, bool $seed): void
    {
        $this->info("Migrating tenant: {$tenant->name} ({$tenant->domain})");

        try {
            // Configure tenant database connection
            $tenant->configureDatabaseConnection();

            // Test connection
            DB::connection('tenant')->getPdo();
            $this->info("✓ Database connection successful");

            // Run migrations
            $command = $fresh ? 'migrate:fresh' : 'migrate';
            $params = [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ];

            if ($seed) {
                $params['--seed'] = true;
            }

            Artisan::call($command, $params);

            $this->info("✓ Migrations completed for {$tenant->name}");

        } catch (\Exception $e) {
            $this->error("✗ Migration failed for {$tenant->name}: " . $e->getMessage());
        }
    }
}
