<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup deployment - run migrations and clear caches';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚀 Setting up deployment...');
        
        // Run migrations
        $this->info('📊 Running migrations...');
        $this->call('migrate', ['--force' => true]);
        
        // Clear caches
        $this->info('🧹 Clearing caches...');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('cache:clear');
        $this->call('view:clear');
        
        // Show current configuration
        $this->info('📋 Current Configuration:');
        $this->info('APP_URL: ' . config('app.url'));
        $this->info('Environment: ' . app()->environment());
        
        $this->info('✅ Deployment setup complete!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Create your first tenant: php artisan tenant:create-simple "Company Name" "subdomain"');
        $this->info('2. Create admin user: php artisan tenant:create-user "subdomain" "Admin" "admin@company.com" "password"');
        
        return 0;
    }
}
