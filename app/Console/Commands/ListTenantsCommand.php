<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class ListTenantsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all tenants';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->info('No tenants found.');
            return 0;
        }

        $this->info("Found {$tenants->count()} tenant(s):");
        $this->newLine();
        
        $headers = ['ID', 'Name', 'Subdomain', 'Database', 'Status'];
        $rows = [];
        
        foreach ($tenants as $tenant) {
            $rows[] = [
                $tenant->id,
                $tenant->name,
                $tenant->subdomain,
                $tenant->database_name,
                $tenant->is_active ? 'Active' : 'Inactive',
            ];
        }
        
        $this->table($headers, $rows);
        
        return 0;
    }
}
