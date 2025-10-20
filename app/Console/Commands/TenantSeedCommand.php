<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:seed 
                            {tenant? : The tenant ID or domain to seed}
                            {--class= : The class name of the root seeder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantIdentifier = $this->argument('tenant');
        $seederClass = $this->option('class');

        if ($tenantIdentifier) {
            // Seed specific tenant
            $tenant = $this->findTenant($tenantIdentifier);
            if (!$tenant) {
                $this->error("Tenant not found: {$tenantIdentifier}");
                return 1;
            }

            $this->seedTenant($tenant, $seederClass);
        } else {
            // Seed all tenants
            $tenants = Tenant::where('is_active', true)->get();
            
            if ($tenants->isEmpty()) {
                $this->info('No active tenants found.');
                return 0;
            }

            $this->info("Found {$tenants->count()} active tenants.");

            foreach ($tenants as $tenant) {
                $this->seedTenant($tenant, $seederClass);
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
     * Seed a specific tenant
     */
    private function seedTenant(Tenant $tenant, ?string $seederClass): void
    {
        $this->info("Seeding tenant: {$tenant->name} ({$tenant->domain})");

        try {
            // Configure tenant database connection
            $tenant->configureDatabaseConnection();

            // Test connection
            DB::connection('tenant')->getPdo();
            $this->info("✓ Database connection successful");

            // Run seeder
            $params = [
                '--database' => 'tenant',
                '--force' => true,
            ];

            if ($seederClass) {
                $params['--class'] = $seederClass;
            }

            Artisan::call('db:seed', $params);

            $this->info("✓ Seeding completed for {$tenant->name}");

        } catch (\Exception $e) {
            $this->error("✗ Seeding failed for {$tenant->name}: " . $e->getMessage());
        }
    }
}
