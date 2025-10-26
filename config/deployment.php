<?php


$host = request()->getHost(); // shop.example.com
$APP_ENV = env('APP_ENV');


$mainDomain = $APP_ENV != "local" ? 'gentecherp.com' : 'localhost';


$subdomain = str_replace('.' . $mainDomain, '', $host);

return [
    /*
    |--------------------------------------------------------------------------
    | Deployment Configuration
    |--------------------------------------------------------------------------
    |
    | Simple configuration for local and live environments.
    | Update the 'live' section when deploying to production.
    |
    */
    
    'live' => [
        // Update these values for your live server
        'domain' => 'gentecherp.com',                    // Your main domain
        'subdomain_pattern' => $subdomain.'', // Subdomain pattern
        'protocol' => 'https',                           // http or https
        'subdirectory' => '/',                // Your app subdirectory
    ],
    
    'local' => [
        // Local development settings (usually don't need to change)
        'domain' => 'localhost',
        'subdomain_pattern' => $subdomain.'.localhost',
        'protocol' => 'http',
        'subdirectory' => '/webwholesale',
    ],
];
