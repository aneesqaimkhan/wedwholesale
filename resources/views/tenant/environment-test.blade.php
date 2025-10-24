<!DOCTYPE html>
<html>
<head>
    <title>Environment Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <h1>🌍 Environment Test</h1>
    
    <div class="info">
        <h3>Current Environment</h3>
        <p><strong>Host:</strong> {{ request()->getHost() }}</p>
        <p><strong>Environment:</strong> {{ app()->environment() }}</p>
        <p><strong>APP_URL:</strong> {{ config('app.url') }}</p>
    </div>
    
    <div class="info">
        <h3>Generated URLs</h3>
        <p><strong>Login:</strong> {{ url('/login') }}</p>
        <p><strong>Register:</strong> {{ url('/register') }}</p>
        <p><strong>Dashboard:</strong> {{ url('/dashboard') }}</p>
    </div>
    
    <div class="info">
        <h3>Configuration</h3>
        <p><strong>Deployment Config:</strong></p>
        <pre>{{ json_encode(config('deployment'), JSON_PRETTY_PRINT) }}</pre>
    </div>
    
    <div class="info">
        <h3>Environment Config</h3>
        <p><strong>Current Environment Settings:</strong></p>
        <pre>{{ json_encode(config('environments'), JSON_PRETTY_PRINT) }}</pre>
    </div>
    
    @if(strpos(request()->getHost(), 'localhost') !== false)
        <div class="success">
            <h3>✅ Local Environment Detected</h3>
            <p>You're running on localhost - this is your development environment.</p>
        </div>
    @else
        <div class="warning">
            <h3>🌐 Live Environment Detected</h3>
            <p>You're running on a live domain - make sure your deployment configuration is correct.</p>
        </div>
    @endif
    
    <div class="info">
        <h3>Test Links</h3>
        <p><a href="{{ url('/login') }}">Login Page</a></p>
        <p><a href="{{ url('/register') }}">Register Page</a></p>
        <p><a href="{{ url('/debug') }}">Debug Page</a></p>
    </div>
</body>
</html>
