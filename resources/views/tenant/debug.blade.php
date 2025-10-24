<!DOCTYPE html>
<html>
<head>
    <title>Debug Info</title>
</head>
<body>
    <h1>Debug Information</h1>
    
    <h2>URL Information:</h2>
    <p><strong>Current URL:</strong> {{ request()->fullUrl() }}</p>
    <p><strong>Base URL:</strong> {{ url('/') }}</p>
    <p><strong>APP_URL:</strong> {{ config('app.url') }}</p>
    <p><strong>Request URL:</strong> {{ request()->url() }}</p>
    <p><strong>Request Path:</strong> {{ request()->path() }}</p>
    
    <h2>Route Information:</h2>
    <p><strong>Subdomain:</strong> {{ request()->route('subdomain') }}</p>
    <p><strong>Route Name:</strong> {{ request()->route()->getName() }}</p>
    
    <h2>Generated URLs:</h2>
    <p><strong>Login URL:</strong> {{ route('tenant.login', ['subdomain' => request()->route('subdomain')]) }}</p>
    <p><strong>Register URL:</strong> {{ route('tenant.register', ['subdomain' => request()->route('subdomain')]) }}</p>
    <p><strong>Dashboard URL:</strong> {{ route('tenant.dashboard', ['subdomain' => request()->route('subdomain')]) }}</p>
    
    <h2>URL Helper Tests:</h2>
    <p><strong>url('/login'):</strong> {{ url('/login') }}</p>
    <p><strong>url('/register'):</strong> {{ url('/register') }}</p>
    <p><strong>url('/dashboard'):</strong> {{ url('/dashboard') }}</p>
    
    <h2>Links:</h2>
    <ul>
        <li><a href="{{ route('tenant.login', ['subdomain' => request()->route('subdomain')]) }}">Login</a></li>
        <li><a href="{{ route('tenant.register', ['subdomain' => request()->route('subdomain')]) }}">Register</a></li>
        <li><a href="{{ route('tenant.dashboard', ['subdomain' => request()->route('subdomain')]) }}">Dashboard</a></li>
    </ul>
</body>
</html>
