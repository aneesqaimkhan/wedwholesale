<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignRolesToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assign-roles {--subdomain= : Tenant subdomain (for multi-tenant)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign roles to existing users based on their old role column';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subdomain = $this->option('subdomain');
        
        if ($subdomain) {
            // For multi-tenant, switch to tenant database
            $tenant = \App\Models\Tenant::where('subdomain', $subdomain)->first();
            if (!$tenant) {
                $this->error("Tenant with subdomain '{$subdomain}' not found!");
                return 1;
            }
            $this->switchToTenantDatabase($tenant);
            $this->info("Processing tenant: {$tenant->name}");
        }

        // Check if roles exist
        if (Role::count() === 0) {
            $this->error('No roles found! Please run the RolePermissionSeeder first.');
            $this->info('Run: php artisan db:seed --class=RolePermissionSeeder');
            if ($subdomain) {
                $this->info("Or for tenant: php artisan tenant:seed {$subdomain} --class=RolePermissionSeeder");
            }
            return 1;
        }

        $this->info('Assigning roles to users...');
        $this->newLine();

        // Map old roles to new role slugs
        $roleMapping = [
            'admin' => 'admin',      // Map to admin role
            'manager' => 'manager',  // Map to manager role
            'user' => 'viewer',      // Map to viewer role (read-only)
        ];

        $assigned = 0;
        $skipped = 0;

        User::whereNotNull('role')->chunk(100, function ($users) use ($roleMapping, &$assigned, &$skipped) {
            foreach ($users as $user) {
                // Skip if user already has roles assigned
                if ($user->roles()->exists()) {
                    $skipped++;
                    continue;
                }

                $oldRole = $user->role;
                $newRoleSlug = $roleMapping[$oldRole] ?? 'viewer';

                $role = Role::where('slug', $newRoleSlug)->first();

                if ($role) {
                    $user->assignRoles([$newRoleSlug]);
                    $this->info("✓ Assigned '{$newRoleSlug}' role to {$user->name} ({$user->email}) - was '{$oldRole}'");
                    $assigned++;
                } else {
                    $this->warn("⚠ Role '{$newRoleSlug}' not found for user {$user->name}");
                    $skipped++;
                }
            }
        });

        $this->newLine();
        $this->info("Completed!");
        $this->info("Assigned: {$assigned} users");
        $this->info("Skipped: {$skipped} users (already have roles or role not found)");

        return 0;
    }

    /**
     * Switch database connection to tenant's database
     */
    private function switchToTenantDatabase($tenant)
    {
        \Illuminate\Support\Facades\Config::set('database.connections.tenant.database', $tenant->database_name);
        \Illuminate\Support\Facades\DB::purge('tenant');
        \Illuminate\Support\Facades\DB::setDefaultConnection('tenant');
    }
}
