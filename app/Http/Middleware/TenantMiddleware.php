<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get tenant from subdomain or header
        $tenant = $this->resolveTenant($request);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found'
            ], 404);
        }

        // Check if tenant is active and license is valid
        if (!$tenant->isLicenseValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant license is invalid or expired'
            ], 403);
        }

        // Configure the tenant database connection
        $tenant->configureDatabaseConnection();

        // Set tenant in request for use in controllers
        $request->merge(['tenant' => $tenant]);

        return $next($request);
    }

    /**
     * Resolve tenant from request
     */
    private function resolveTenant(Request $request): ?Tenant
    {
        // Method 1: From subdomain
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);
        
        if ($subdomain) {
            $tenant = Tenant::findByDomain($subdomain);
            if ($tenant) {
                return $tenant;
            }
        }

        // Method 2: From X-Tenant header
        $tenantId = $request->header('X-Tenant-ID');
        if ($tenantId) {
            return Tenant::find($tenantId);
        }

        // Method 3: From X-Tenant-Domain header
        $tenantDomain = $request->header('X-Tenant-Domain');
        if ($tenantDomain) {
            return Tenant::findByDomain($tenantDomain);
        }

        return null;
    }

    /**
     * Extract subdomain from host
     */
    private function extractSubdomain(string $host): ?string
    {
        $parts = explode('.', $host);
        
        // If we have at least 3 parts (subdomain.domain.tld), return the subdomain
        if (count($parts) >= 3) {
            return $parts[0];
        }

        return null;
    }
}
