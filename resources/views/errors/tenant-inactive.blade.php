<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Inactive - Medical Wholesale Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            margin: 20px;
        }
        .error-icon {
            font-size: 4rem;
            color: #e67e22;
            margin-bottom: 1rem;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        p {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .tenant-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }
        .tenant-name {
            font-weight: bold;
            color: #2c3e50;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
            margin-top: 1rem;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠️</div>
        <h1>Account Inactive</h1>
        <p>Your account is currently inactive or your license has expired.</p>
        
        @if(isset($tenant))
        <div class="tenant-info">
            <div class="tenant-name">{{ $tenant->company_name }}</div>
            <p>License Period: {{ $tenant->license_start_date->format('M d, Y') }} - {{ $tenant->license_end_date->format('M d, Y') }}</p>
        </div>
        @endif
        
        <p>Please contact your system administrator to reactivate your account or renew your license.</p>
        <a href="mailto:support@medwholesale.com" class="btn">Contact Support</a>
    </div>
</body>
</html>
