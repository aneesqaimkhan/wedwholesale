<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $tenant->company_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #2c3e50;
        }
        
        .header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo h1 {
            color: #667eea;
            font-size: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .welcome-section h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .welcome-section p {
            color: #7f8c8d;
            line-height: 1.6;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .module-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        
        .module-card h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .module-card p {
            color: #7f8c8d;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .module-btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        
        .module-btn:hover {
            background: #5a6fd8;
        }
        
        .module-btn.disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        
        .tenant-info {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
            border-left: 4px solid #3498db;
        }
        
        .tenant-info h4 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .tenant-info p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin: 0.25rem 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1>🏥 {{ $tenant->company_name }}</h1>
        </div>
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <span>{{ $user->name }}</span>
            <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    
    <div class="main-content">
        <div class="welcome-section">
            <h2>Welcome back, {{ $user->name }}!</h2>
            <p>You are logged in to <strong>{{ $tenant->company_name }}</strong> management system. From here you can access all the modules and features of your wholesale management system.</p>
            
            <div class="tenant-info">
                <h4>Account Information</h4>
                <p><strong>Company:</strong> {{ $tenant->company_name }}</p>
                <p><strong>License Period:</strong> {{ $tenant->license_start_date->format('M d, Y') }} - {{ $tenant->license_end_date->format('M d, Y') }}</p>
                <p><strong>Contact:</strong> {{ $tenant->contact_email }}</p>
                @if($tenant->contact_phone)
                <p><strong>Phone:</strong> {{ $tenant->contact_phone }}</p>
                @endif
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-number">0</div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number">0</div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏪</div>
                <div class="stat-number">0</div>
                <div class="stat-label">Total Suppliers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-number">0</div>
                <div class="stat-label">Total Sales</div>
            </div>
        </div>
        
        <div class="modules-grid">
            <div class="module-card">
                <h3>📦 Products Management</h3>
                <p>Manage your product inventory, categories, pricing, and stock levels.</p>
                <a href="#" class="module-btn disabled">Coming Soon</a>
            </div>
            
            <div class="module-card">
                <h3>👥 Customers Management</h3>
                <p>Maintain customer database, track sales history, and manage relationships.</p>
                <a href="#" class="module-btn disabled">Coming Soon</a>
            </div>
            
            <div class="module-card">
                <h3>🏪 Suppliers Management</h3>
                <p>Manage supplier information, purchase orders, and vendor relationships.</p>
                <a href="#" class="module-btn disabled">Coming Soon</a>
            </div>
            
            <div class="module-card">
                <h3>📊 Sales Management</h3>
                <p>Process sales orders, generate invoices, and track sales performance.</p>
                <a href="#" class="module-btn disabled">Coming Soon</a>
            </div>
            
            <div class="module-card">
                <h3>🛒 Purchase Management</h3>
                <p>Create purchase orders, manage inventory receipts, and track purchases.</p>
                <a href="#" class="module-btn disabled">Coming Soon</a>
            </div>
            
            <div class="module-card">
                <h3>📈 Reports & Analytics</h3>
                <p>Generate comprehensive reports and analytics for business insights.</p>
                <a href="#" class="module-btn disabled">Coming Soon</a>
            </div>
        </div>
    </div>
</body>
</html>
