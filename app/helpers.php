<?php

if (!function_exists('route_include_subdirectory')) {
    /**
     * Generate a route URL that includes the subdirectory path
     * 
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route_include_subdirectory($name, $parameters = [], $absolute = true)
    {
        $url = route($name, $parameters, $absolute);
        $rootUrl = config('app.url');
        
        if ($rootUrl) {
            $parsed = parse_url($rootUrl);
            if (isset($parsed['path']) && $parsed['path'] !== '/') {
                $subdirectory = rtrim($parsed['path'], '/');
                // Check if subdirectory is already in the URL
                if (strpos($url, $subdirectory) === false) {
                    // Insert subdirectory into the path
                    $urlParsed = parse_url($url);
                    if (isset($urlParsed['path'])) {
                        $newPath = $subdirectory . $urlParsed['path'];
                        $scheme = isset($urlParsed['scheme']) ? $urlParsed['scheme'] . '://' : '';
                        $host = isset($urlParsed['host']) ? $urlParsed['host'] : '';
                        $port = isset($urlParsed['port']) ? ':' . $urlParsed['port'] : '';
                        $query = isset($urlParsed['query']) ? '?' . $urlParsed['query'] : '';
                        $fragment = isset($urlParsed['fragment']) ? '#' . $urlParsed['fragment'] : '';
                        $url = $scheme . $host . $port . $newPath . $query . $fragment;
                    }
                }
            }
        }
        
        return $url;
    }
}

