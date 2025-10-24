<?php

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
        'domain' => 'yourdomain.com',                    // Your main domain
        'subdomain_pattern' => '{subdomain}.yourdomain.com', // Subdomain pattern
        'protocol' => 'https',                           // http or https
        'subdirectory' => '/webwholesale',                // Your app subdirectory
    ],
    
    'local' => [
        // Local development settings (usually don't need to change)
        'domain' => 'localhost',
        'subdomain_pattern' => '{subdomain}.localhost',
        'protocol' => 'http',
        'subdirectory' => '/webwholesale',
    ],
];
