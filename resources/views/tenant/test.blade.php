<!DOCTYPE html>
<html>
<head>
    <title>Tenant Test</title>
</head>
<body>
    <h1>Tenant Test Page</h1>
    <p>Subdomain: {{ request()->route('subdomain') }}</p>
    <p>Host: {{ request()->getHost() }}</p>
    <p>Current Tenant: {{ request()->attributes->get('current_tenant')->name ?? 'Not found' }}</p>
    
    <h2>Available Routes:</h2>
    <ul>
        <li><a href="{{ route('tenant.login', ['subdomain' => request()->route('subdomain')]) }}">Login</a></li>
        <li><a href="{{ route('tenant.register', ['subdomain' => request()->route('subdomain')]) }}">Register</a></li>
        <li><a href="{{ route('tenant.dashboard', ['subdomain' => request()->route('subdomain')]) }}">Dashboard</a></li>
    </ul>
</body>
</html>
