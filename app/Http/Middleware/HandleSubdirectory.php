<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        
        // Set the correct APP_URL for subdomain requests with subdirectory
        if (strpos($host, '.localhost') !== false) {
            $subdomain = explode('.', $host)[0];
            $baseUrl = "http://{$subdomain}.localhost/webwholesale";
            config(['app.url' => $baseUrl]);
            app('url')->forceRootUrl($baseUrl);
        } elseif ($host === 'localhost' || $host === '127.0.0.1') {
            // Handle main domain with subdirectory
            $baseUrl = 'http://localhost/webwholesale';
            config(['app.url' => $baseUrl]);
            app('url')->forceRootUrl($baseUrl);
        }
        
        return $next($request);
    }
}
