<?php

$deployment = config('deployment');

return [
    'local' => [
        'app_url' => $deployment['local']['protocol'] . '://' . $deployment['local']['domain'] . $deployment['local']['subdirectory'],
        'subdomain_url' => $deployment['local']['protocol'] . '://' . $deployment['local']['subdomain_pattern'] . $deployment['local']['subdirectory'],
        'database_host' => '127.0.0.1',
        'database_port' => '3306',
    ],
    
    'live' => [
        'app_url' => $deployment['live']['protocol'] . '://' . $deployment['live']['domain'] . $deployment['live']['subdirectory'],
        'subdomain_url' => $deployment['live']['protocol'] . '://' . $deployment['live']['subdomain_pattern'] . $deployment['live']['subdirectory'],
        'database_host' => '127.0.0.1',
        'database_port' => '3306',
    ],
];
