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
        
        // Connect to master database to find tenant
        $tenant = Tenant::where('subdomain', $subdomain)
                       ->where('is_active', true)
                       ->first();
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        // Switch to tenant database
        $this->switchToTenantDatabase($tenant);
        
        // Store current tenant in request for easy access
        $request->attributes->set('current_tenant', $tenant);
        
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
    }
}
