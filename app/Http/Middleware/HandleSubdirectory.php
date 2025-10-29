<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class HandleSubdirectory
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
        $environment = $this->detectEnvironment($host);
        $config = config("environments.{$environment}");
        $deploymentConfig = config("deployment.{$environment}");
        
        if ($config && $deploymentConfig) {
            // Handle subdomain requests
            if (strpos($host, '.') !== false && !in_array($host, ['localhost', '127.0.0.1'])) {
                $subdomain = explode('.', $host)[0];
                $baseUrl = str_replace('{subdomain}', $subdomain, $config['subdomain_url']);
            } else {
                // Handle main domain requests
                $baseUrl = $config['app_url'];
            }
            
            config(['app.url' => $baseUrl]);
            URL::forceRootUrl($baseUrl);
            
            // Parse the baseUrl to extract the path component (subdirectory)
            $parsedUrl = parse_url($baseUrl);
            if (isset($parsedUrl['path']) && $parsedUrl['path'] !== '/') {
                $subdirectory = rtrim($parsedUrl['path'], '/');
                $request->attributes->set('subdirectory', $subdirectory);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Detect environment based on host
     */
    private function detectEnvironment($host)
    {
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            return 'local';
        }
        
        return 'live';
    }
}
