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

if (!function_exists('user_has_permission')) {
    /**
     * Check if the authenticated user has a specific permission
     * 
     * @param  string  $permission
     * @return bool
     */
    function user_has_permission($permission)
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->hasPermission($permission);
    }
}

if (!function_exists('user_has_role')) {
    /**
     * Check if the authenticated user has a specific role
     * 
     * @param  string|array  $role
     * @return bool
     */
    function user_has_role($role)
    {
        if (!auth()->check()) {
            return false;
        }
        
        if (is_array($role)) {
            return auth()->user()->hasAnyRole($role);
        }
        
        return auth()->user()->hasRole($role);
    }
}

if (!function_exists('user_has_any_permission')) {
    /**
     * Check if the authenticated user has any of the given permissions
     * 
     * @param  array  $permissions
     * @return bool
     */
    function user_has_any_permission(array $permissions)
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->hasAnyPermission($permissions);
    }
}

