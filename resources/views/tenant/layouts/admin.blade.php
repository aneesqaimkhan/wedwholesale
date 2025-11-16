<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        @media print {
            @page {
                margin: 0;
                size: auto;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
            }
            
            /* Hide browser default print headers/footers (URL and page numbers) */
            body::before,
            body::after,
            body::first-line {
                display: none !important;
            }
            
            /* Hide all UI elements when printing */
            .sidebar,
            .top-navbar,
            .menu-toggle,
            .overlay,
            .btn,
            .btn-logout,
            .user-menu,
            .navbar-title,
            .alert,
            .page-header,
            .page-title,
            .page-subtitle {
                display: none !important;
            }
            
            .admin-container {
                display: block !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                background: white !important;
            }
            
            .content-area {
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .card {
                box-shadow: none !important;
                border: none !important;
                padding: 10px !important;
                margin: 0 !important;
                page-break-inside: avoid;
            }
            
            /* Prevent page breaks in tables */
            table {
                page-break-inside: avoid;
            }
            
            /* Hide any print-specific elements */
            .no-print {
                display: none !important;
            }
        }
    </style>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #6D2D9D 0%, #4a1a6b 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s, width 0.3s;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        @media (min-width: 769px) {
            .sidebar.closed {
                transform: translateX(0);
                width: 0;
            }
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
			font-size: 18px;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: block;
            padding: 12px 20px;
			font-size: 13px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #fff;
        }

        .nav-link[type="submit"]:hover {
            background: rgba(220, 53, 69, 0.3) !important;
            border-left-color: #dc3545 !important;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            background: #f8f9fa;
            transition: margin-left 0.3s;
        }

        @media (min-width: 769px) {
            .sidebar.closed ~ .main-content {
                margin-left: 0;
            }
        }

        .top-navbar {
            background: white;
			padding: 2px 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
			font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            color: #666;
            font-size: 14px;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: #c82333;
        }

        .content-area {
            padding: 5px 10px;
        }

        .page-header {
            margin-bottom: 15px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #666;
            font-size: 13px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 6px 16px;
            background: #6D2D9D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #5a2470;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 12px;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 11px;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .shortcut-hint {
            margin-left: 6px;
            font-size: 11px;
            color: rgba(255,255,255,0.7);
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #6D2D9D;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .menu-toggle {
            display: block;
            background: #6D2D9D;
            color: white;
            border: none;
			font-size: 18px;
            cursor: pointer;
			padding: 4px 8px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .menu-toggle:hover {
            background: #5a2470;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }

            .sidebar.open,
            .sidebar.closed.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                flex-wrap: wrap;
            }

            .user-menu {
                width: 100%;
                margin-top: 10px;
                justify-content: space-between;
            }

            .content-area {
                padding: 5px;
            }
        }

        @media (min-width: 769px) {
            .overlay {
                display: none !important;
            }

            .sidebar.closed {
                width: 0;
                overflow: hidden;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Overlay for mobile -->
        <div class="overlay" id="overlay"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route_include_subdirectory('tenant.dashboard') }}" class="sidebar-brand">
                    Admin Panel
                </a>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('tenant.dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" data-shortcut-key="d">
                        <i>🏠</i> Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('customers.index') }}" class="nav-link {{ request()->is('customers*') ? 'active' : '' }}" data-shortcut-key="c">
                        <i>👥</i> Customers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('suppliers.index') }}" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}" data-shortcut-key="u">
                        <i>🏭</i> Suppliers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('salesmen.index') }}" class="nav-link {{ request()->is('salesmen*') ? 'active' : '' }}" data-shortcut-key="m">
                        <i>👨‍💼</i> Salesmen
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('areas.index') }}" class="nav-link {{ request()->is('areas*') ? 'active' : '' }}" data-shortcut-key="a">
                        <i>📍</i> Areas
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}" data-shortcut-key="p">
                        <i>📦</i> Products
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('sales_invoices.index') }}" class="nav-link {{ request()->is('sales-invoices*') ? 'active' : '' }}" data-shortcut-key="i">
                        <i>🧾</i> Sales Invoices
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('companies.index') }}" class="nav-link {{ request()->is('companies*') ? 'active' : '' }}" data-shortcut-key="o">
                        <i>🏢</i> Companies
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('purchases.index') }}" class="nav-link {{ request()->is('purchases*') ? 'active' : '' }}" data-shortcut-key="r">
                        <i>🛒</i> Purchases
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('receipt_payments.index') }}" class="nav-link {{ request()->is('receipt-payments*') ? 'active' : '' }}" data-shortcut-key="t">
                        <i>💳</i> Receipt Payments
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('expense_types.index') }}" class="nav-link {{ request()->is('expense-types*') ? 'active' : '' }}" data-shortcut-key="e">
                        <i>🏷️</i> Expense Types
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('expenses.index') }}" class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}" data-shortcut-key="x">
                        <i>💰</i> Expenses
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route_include_subdirectory('list_status_manual.index') }}" class="nav-link {{ request()->is('list-status-manual*') ? 'active' : '' }}" data-shortcut-key="l">
                        <i>📊</i> List Status Manual
                    </a>
                </div>
                <div style="margin-top: 20px; padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <div style="color: rgba(255,255,255,0.9); font-size: 13px; margin-bottom: 10px;">
                        Welcome, {{ auth()->user()->name }}
                    </div>
                    <form method="POST" action="{{ route_include_subdirectory('tenant.logout', ['subdomain' => request()->route('subdomain')]) }}" style="display: inline; width: 100%;">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: rgba(220, 53, 69, 0.2); border-left-color: #dc3545;">
                            <i>🚪</i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <div class="navbar-title">@yield('page-title', 'Dashboard')</div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Menu toggle functionality for both mobile and desktop
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const isMobile = window.innerWidth <= 768;

        function openMenu() {
            if (isMobile) {
                sidebar.classList.add('open');
                overlay.classList.add('active');
            } else {
                sidebar.classList.remove('closed');
            }
        }

        function closeMenu() {
            if (isMobile) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.add('closed');
            }
        }

        function toggleMenu() {
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                if (sidebar.classList.contains('open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            } else {
                if (sidebar.classList.contains('closed')) {
                    sidebar.classList.remove('closed');
                } else {
                    sidebar.classList.add('closed');
                }
            }
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', toggleMenu);
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                if (isMobile) {
                    closeMenu();
                }
            });
        }

        // Close menu when clicking on nav links (mobile only)
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (isMobile) {
                    closeMenu();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const isMobile = window.innerWidth <= 768;
            if (!isMobile) {
                overlay.classList.remove('active');
            }
        });

        // Sidebar menu keyboard shortcuts
        (function() {
            const links = document.querySelectorAll('.nav-link[data-shortcut-key]');
            links.forEach(link => {
                const key = (link.getAttribute('data-shortcut-key') || '').toUpperCase();
                if (!key) return;
                // Add visible hint once
                if (!link.querySelector('.shortcut-hint')) {
                    const hint = document.createElement('span');
                    hint.className = 'shortcut-hint';
                    hint.textContent = `(Alt+${key})`;
                    link.appendChild(hint);
                }
                // Tooltip
                if (!link.title) {
                    link.title = `Shortcut: Alt+${key}`;
                }
            });

            // Navigate on Alt+Key when not typing in inputs
            window.addEventListener('keydown', function(e) {
                if (!e.altKey || e.ctrlKey || e.metaKey) return;
                const tag = (e.target && e.target.tagName || '').toLowerCase();
                if (tag === 'input' || tag === 'textarea' || tag === 'select' || (e.target && e.target.isContentEditable)) return;
                const key = (e.key || '').toLowerCase();
                if (!key) return;
                const link = document.querySelector(`.nav-link[data-shortcut-key="${key}"]`);
                if (link && link.getAttribute('href')) {
                    e.preventDefault();
                    window.location.href = link.getAttribute('href');
                }
            });
        })();

        // Global CRUD shortcuts (Alt+1..4) based on current module
        (function() {
            function getActiveModuleLink() {
                // Prefer the nav-link marked active
                let link = document.querySelector('.sidebar .nav-link.active');
                if (link) return link;
                // Fallback: best match current location
                const links = Array.from(document.querySelectorAll('.sidebar .nav-link[href]'));
                const current = window.location.pathname;
                let best = null;
                let bestLen = -1;
                links.forEach(l => {
                    try {
                        const u = new URL(l.href, window.location.origin);
                        if (current.startsWith(u.pathname) && u.pathname.length > bestLen) {
                            best = l;
                            bestLen = u.pathname.length;
                        }
                    } catch (e) {}
                });
                return best;
            }

            function getModuleBaseHref() {
                const link = getActiveModuleLink();
                if (!link) return null;
                try {
                    const u = new URL(link.getAttribute('href'), window.location.origin);
                    return u.origin + u.pathname.replace(/\/+$/,''); // no trailing slash
                } catch (e) {
                    return null;
                }
            }

            function getCurrentRecordId() {
                // Standard hooks any module page can set
                if (window.currentRecordId) return String(window.currentRecordId);
                // Look for common attributes in row or page
                const el = document.querySelector('[data-record-id]') || document.getElementById('current_record_id');
                if (el) {
                    const val = el.getAttribute ? el.getAttribute('data-record-id') : (el.value || el.textContent || '').trim();
                    if (val) return String(val);
                }
                // Selected row in a table
                const selectedRow = document.querySelector('tr.selected, tr.active, tr.row-selected');
                if (selectedRow && selectedRow.getAttribute('data-id')) return String(selectedRow.getAttribute('data-id'));
                return null;
            }

            function navigate(url) {
                if (!url) return;
                window.location.href = url;
            }

            window.addEventListener('keydown', function(e) {
                if (!e.altKey || e.ctrlKey || e.metaKey || e.shiftKey) return;
                const tag = (e.target && e.target.tagName || '').toLowerCase();
                if (tag === 'input' || tag === 'textarea' || tag === 'select' || (e.target && e.target.isContentEditable)) return;

                const base = getModuleBaseHref();
                if (!base) return;

                // Alt+1 => Create
                if (e.key === '1') {
                    e.preventDefault();
                    navigate(base + '/create');
                    return;
                }
                // Alt+2 => Index/List
                if (e.key === '2') {
                    e.preventDefault();
                    navigate(base);
                    return;
                }
                // Alt+3 => Edit (needs record id)
                if (e.key === '3') {
                    e.preventDefault();
                    const id = getCurrentRecordId();
                    if (!id) {
                        alert('Select a record first to edit (no record id detected).');
                        return;
                    }
                    navigate(base + '/' + encodeURIComponent(id) + '/edit');
                    return;
                }
                // Alt+4 => Delete (needs record id)
                if (e.key === '4') {
                    e.preventDefault();
                    const id = getCurrentRecordId();
                    if (!id) {
                        alert('Select a record first to delete (no record id detected).');
                        return;
                    }
                    if (!confirm('Are you sure you want to delete this record?')) return;

                    // Try to find an existing delete form for this id; else construct one
                    let form = document.querySelector(`form[data-delete-form-for="${id}"]`);
                    if (!form) {
                        form = document.createElement('form');
                        form.method = 'POST';
                        form.action = base + '/' + encodeURIComponent(id);
                        form.style.display = 'none';
                        form.setAttribute('data-delete-form-for', id);
                        // CSRF + method spoofing
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        const iCsrf = document.createElement('input');
                        iCsrf.type = 'hidden';
                        iCsrf.name = '_token';
                        iCsrf.value = token;
                        const iMethod = document.createElement('input');
                        iMethod.type = 'hidden';
                        iMethod.name = '_method';
                        iMethod.value = 'DELETE';
                        form.appendChild(iCsrf);
                        form.appendChild(iMethod);
                        document.body.appendChild(form);
                    }
                    form.submit();
                    return;
                }
            });
        })();
    </script>
</body>
</html>
