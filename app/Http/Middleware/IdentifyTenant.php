<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Skip tenant identification for main domain
        if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, '.localhost') === false) {
            return $next($request);
        }
        
        // Extract subdomain from host (e.g., "test.localhost" -> "test")
        $subdomain = explode('.', $host)[0];
        
        try {
            // Connect to master database to find tenant
            $tenant = Tenant::where('subdomain', $subdomain)
                           ->where('is_active', true)
                           ->first();
            
            // Log for debugging
            \Log::info('Tenant lookup', [
                'subdomain' => $subdomain,
                'tenant_found' => $tenant ? true : false,
                'tenant_name' => $tenant->name ?? null
            ]);
            
            if (!$tenant) {
                \Log::warning('Tenant not found', ['subdomain' => $subdomain]);
                abort(404, 'Tenant not found');
            }
            
            // Switch to tenant database
            $this->switchToTenantDatabase($tenant);
            
            // Verify tenant database connection
            $this->verifyTenantConnection();
            
            // Store current tenant in request for easy access
            $request->attributes->set('current_tenant', $tenant);
            
        } catch (\Exception $e) {
            \Log::error('Tenant identification failed', [
                'subdomain' => $subdomain,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Database connection failed',
                'message' => $e->getMessage(),
                'subdomain' => $subdomain
            ], 500);
        }
        
        return $next($request);
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
        
        \Log::info('Switched to tenant database', [
            'tenant' => $tenant->name,
            'database' => $tenant->database_name,
            'host' => $tenant->database_host
        ]);
    }
    
    /**
     * Verify tenant database connection
     */
    private function verifyTenantConnection()
    {
        try {
            // Try to run a simple query to verify connection
            DB::select('SELECT 1');
            
            \Log::info('Tenant database connection verified successfully');
            
        } catch (\Exception $e) {
            \Log::error('Failed to verify tenant database connection', [
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to connect to tenant database: ' . $e->getMessage());
        }
    }
}
