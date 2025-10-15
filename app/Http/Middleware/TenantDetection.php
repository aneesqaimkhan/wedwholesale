<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Illuminate\Support\Facades\View;

class TenantDetection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract subdomain from the request
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);
        
        // For development: allow tenant parameter for direct access
        if (!$subdomain && $request->has('tenant')) {
            $subdomain = $request->get('tenant');
        }
        
        if (!$subdomain) {
            // If no subdomain, show tenant selection for development
            if (app()->environment('local')) {
                return $this->showTenantSelection();
            }
            return response()->view('errors.tenant-not-found', [], 404);
        }
        
        // Find tenant by subdomain
        $tenant = Tenant::findByDomain($subdomain);
        
        if (!$tenant) {
            // Tenant not found
            return response()->view('errors.tenant-not-found', [], 404);
        }
        
        // Check if tenant is active and license is valid
        if (!$tenant->isLicenseValid()) {
            // Tenant license expired or inactive
            return response()->view('errors.tenant-inactive', ['tenant' => $tenant], 403);
        }
        
        // Configure database connection for this tenant
        $tenant->configureDatabaseConnection();
        
        // Store tenant in request for later use
        $request->merge(['tenant' => $tenant]);
        
        // Share tenant data with all views
        View::share('tenant', $tenant);
        
        return $next($request);
    }
    
    /**
     * Extract subdomain from host
     */
    private function extractSubdomain(string $host): ?string
    {
        // Remove localhost and port if present
        $host = preg_replace('/:\d+$/', '', $host);
        
        // For local development, handle medwholesale.local format
        if (str_contains($host, 'medwholesale.local')) {
            $parts = explode('.', $host);
            if (count($parts) >= 3 && $parts[1] === 'medwholesale' && $parts[2] === 'local') {
                return $parts[0];
            }
        }
        
        // For production, handle subdomain.domain.com format
        $parts = explode('.', $host);
        if (count($parts) >= 3) {
            return $parts[0];
        }
        
        return null;
    }
    
    /**
     * Show tenant selection page for development
     */
    private function showTenantSelection(): Response
    {
        $tenants = Tenant::where('is_active', true)->get();
        
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Tenant - Medical Wholesale Management</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 600px;
            margin: 20px;
        }
        .logo {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        .tenant-list {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .tenant-card {
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: left;
            transition: all 0.3s;
            cursor: pointer;
        }
        .tenant-card:hover {
            border-color: #3498db;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
        }
        .tenant-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .tenant-company {
            color: #7f8c8d;
            margin-bottom: 0.5rem;
        }
        .tenant-email {
            color: #95a5a6;
            font-size: 0.9rem;
        }
        .instructions {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🏥</div>
        <h1>Medical Wholesale Management</h1>
        <div class="instructions">
            <strong>Development Mode:</strong> Select a tenant to access the application.<br>
            <small>For production, use subdomains like demo.medwholesale.local</small>
        </div>
        <div class="tenant-list">';
        
        foreach ($tenants as $tenant) {
            $html .= '<div class="tenant-card" onclick="window.location.href=\'?tenant=' . $tenant->domain . '\'">
                <div class="tenant-name">' . $tenant->name . '</div>
                <div class="tenant-company">' . $tenant->company_name . '</div>
                <div class="tenant-email">' . $tenant->contact_email . '</div>
            </div>';
        }
        
        $html .= '</div>
        <p><small>Available tenants: ' . $tenants->count() . '</small></p>
    </div>
</body>
</html>';
        
        return response($html);
    }
}
