

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1 class="page-title">Welcome to Your Dashboard!</h1>
    <p class="page-subtitle">Overview of your business performance and statistics</p>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #6D2D9D 0%, #8B4BC7 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Sales</div>
        <div style="font-size: 28px; font-weight: bold;"><?php echo e(number_format($stats['total_sales'] ?? 0, 2)); ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;"><?php echo e($stats['total_sales_invoices'] ?? 0); ?> Invoices</div>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #28a745 0%, #34ce57 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Purchases</div>
        <div style="font-size: 28px; font-weight: bold;"><?php echo e(number_format($stats['total_purchases'] ?? 0, 2)); ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;"><?php echo e($stats['total_purchases'] ?? 0); ?> Orders</div>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #dc3545 0%, #e4606d 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Expenses</div>
        <div style="font-size: 28px; font-weight: bold;"><?php echo e(number_format($stats['total_expenses_amount'] ?? 0, 2)); ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;"><?php echo e($stats['total_expenses'] ?? 0); ?> Records</div>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #17a2b8 0%, #3fc1d8 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Customers</div>
        <div style="font-size: 28px; font-weight: bold;"><?php echo e(number_format($stats['total_customers'] ?? 0)); ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">Active Accounts</div>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #ffc107 0%, #ffd54f 100%); color: #333;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Products</div>
        <div style="font-size: 28px; font-weight: bold;"><?php echo e(number_format($stats['total_products'] ?? 0)); ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">In Stock</div>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #6f42c1 0%, #9d7ce8 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Suppliers</div>
        <div style="font-size: 28px; font-weight: bold;"><?php echo e(number_format($stats['total_suppliers'] ?? 0)); ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">Vendors</div>
    </div>
</div>

<!-- Charts Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Sales Trend Chart -->
    <div class="card">
        <h3 style="margin-top: 0; color: #333; margin-bottom: 20px;">Sales Trend (Last 12 Months)</h3>
        <canvas id="salesChart" style="max-height: 300px;"></canvas>
    </div>
    
    <!-- Purchases Trend Chart -->
    <div class="card">
        <h3 style="margin-top: 0; color: #333; margin-bottom: 20px;">Purchases Trend (Last 12 Months)</h3>
        <canvas id="purchasesChart" style="max-height: 300px;"></canvas>
    </div>
    
    <!-- Sales vs Purchases Comparison -->
    <div class="card">
        <h3 style="margin-top: 0; color: #333; margin-bottom: 20px;">Sales vs Purchases (Last 6 Months)</h3>
        <canvas id="comparisonChart" style="max-height: 300px;"></canvas>
    </div>
    
    <!-- Top Customers Chart -->
    <div class="card">
        <h3 style="margin-top: 0; color: #333; margin-bottom: 20px;">Top Customers (Last 30 Days)</h3>
        <canvas id="topCustomersChart" style="max-height: 300px;"></canvas>
    </div>
    
    <!-- Top Products Chart -->
    <div class="card">
        <h3 style="margin-top: 0; color: #333; margin-bottom: 20px;">Top Products (Last 30 Days)</h3>
        <canvas id="topProductsChart" style="max-height: 300px;"></canvas>
    </div>
    
    <!-- Expenses by Type Chart -->
    <div class="card">
        <h3 style="margin-top: 0; color: #333; margin-bottom: 20px;">Expenses by Type</h3>
        <canvas id="expensesChart" style="max-height: 300px;"></canvas>
    </div>
</div>

<!-- User Profile Card -->
<div class="card" style="margin-top: 20px;">
    <h3 style="margin-top: 0; color: #333;">Your Profile Information</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Name:</span>
            <span style="color: #666;"><?php echo e($user->name); ?></span>
        </div>
        
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Email:</span>
            <span style="color: #666;"><?php echo e($user->email); ?></span>
        </div>
        
        <?php if($user->phone): ?>
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Phone:</span>
            <span style="color: #666;"><?php echo e($user->phone); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if($user->company): ?>
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Company:</span>
            <span style="color: #666;"><?php echo e($user->company); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if($user->address): ?>
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Address:</span>
            <span style="color: #666;"><?php echo e($user->address); ?></span>
        </div>
        <?php endif; ?>
        
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Role:</span>
            <span style="color: #666; text-transform: capitalize;"><?php echo e($user->role); ?></span>
        </div>
        
        <div style="display: flex; margin-bottom: 10px;">
            <span style="font-weight: 500; color: #333; width: 120px;">Status:</span>
            <span style="color: <?php echo e($user->is_active ? '#28a745' : '#dc3545'); ?>;">
                <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

            </span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
    <div class="card">
        <h3 style="margin-top: 0; color: #333;">Quick Actions</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="<?php echo e(url('/customers/create')); ?>" class="btn">Add New Customer</a>
            <a href="<?php echo e(url('/salesmen/create')); ?>" class="btn">Add New Salesman</a>
            <a href="<?php echo e(url('/products/create')); ?>" class="btn">Add New Product</a>
            <a href="<?php echo e(url('/sales-invoices/create')); ?>" class="btn">Create Sales Invoice</a>
        </div>
    </div>
    
    <div class="card">
        <h3 style="margin-top: 0; color: #333;">Quick Links</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="<?php echo e(url('/sales-invoices')); ?>" class="btn" style="background: #6D2D9D;">View Sales Invoices</a>
            <a href="<?php echo e(url('/purchases')); ?>" class="btn" style="background: #28a745;">View Purchases</a>
            <a href="<?php echo e(url('/reports')); ?>" class="btn" style="background: #17a2b8;">View Reports</a>
            <a href="<?php echo e(url('/expenses')); ?>" class="btn" style="background: #dc3545;">View Expenses</a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart.js configuration
    Chart.defaults.color = '#666';
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.font.size = 12;

    // Sales Trend Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($salesChartData['labels'] ?? [], 15, 512) ?>,
                datasets: [{
                    label: 'Sales Amount',
                    data: <?php echo json_encode($salesChartData['data'] ?? [], 15, 512) ?>,
                    borderColor: '#6D2D9D',
                    backgroundColor: 'rgba(109, 45, 157, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Purchases Trend Chart
    const purchasesCtx = document.getElementById('purchasesChart');
    if (purchasesCtx) {
        new Chart(purchasesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($purchasesChartData['labels'] ?? [], 15, 512) ?>,
                datasets: [{
                    label: 'Purchases Amount',
                    data: <?php echo json_encode($purchasesChartData['data'] ?? [], 15, 512) ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Sales vs Purchases Comparison Chart
    const comparisonCtx = document.getElementById('comparisonChart');
    if (comparisonCtx) {
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($comparisonData['labels'] ?? [], 15, 512) ?>,
                datasets: [
                    {
                        label: 'Sales',
                        data: <?php echo json_encode($comparisonData['sales'] ?? [], 15, 512) ?>,
                        backgroundColor: 'rgba(109, 45, 157, 0.8)',
                        borderColor: '#6D2D9D',
                        borderWidth: 1
                    },
                    {
                        label: 'Purchases',
                        data: <?php echo json_encode($comparisonData['purchases'] ?? [], 15, 512) ?>,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: '#28a745',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Top Customers Chart
    const topCustomersCtx = document.getElementById('topCustomersChart');
    if (topCustomersCtx) {
        new Chart(topCustomersCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($topCustomersChartData['labels'] ?? [], 15, 512) ?>,
                datasets: [{
                    label: 'Sales Amount',
                    data: <?php echo json_encode($topCustomersChartData['data'] ?? [], 15, 512) ?>,
                    backgroundColor: 'rgba(23, 162, 184, 0.8)',
                    borderColor: '#17a2b8',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart');
    if (topProductsCtx) {
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($topProductsChartData['labels'] ?? [], 15, 512) ?>,
                datasets: [{
                    label: 'Sales Amount',
                    data: <?php echo json_encode($topProductsChartData['data'] ?? [], 15, 512) ?>,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: '#ffc107',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Expenses by Type Chart
    const expensesCtx = document.getElementById('expensesChart');
    if (expensesCtx) {
        const expenseColors = [
            'rgba(220, 53, 69, 0.8)',
            'rgba(255, 193, 7, 0.8)',
            'rgba(23, 162, 184, 0.8)',
            'rgba(40, 167, 69, 0.8)',
            'rgba(109, 45, 157, 0.8)',
            'rgba(111, 66, 193, 0.8)',
            'rgba(255, 87, 34, 0.8)',
            'rgba(0, 188, 212, 0.8)'
        ];
        
        const expenseLabels = <?php echo json_encode($expensesChartData['labels'] ?? [], 15, 512) ?>;
        const expenseData = <?php echo json_encode($expensesChartData['data'] ?? [], 15, 512) ?>;
        
        new Chart(expensesCtx, {
            type: 'doughnut',
            data: {
                labels: expenseLabels,
                datasets: [{
                    label: 'Expenses',
                    data: expenseData,
                    backgroundColor: expenseColors.slice(0, expenseLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\webwholesale\resources\views/tenant/dashboard.blade.php ENDPATH**/ ?>