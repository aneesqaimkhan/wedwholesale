@extends('tenant.layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="card">
    <div class="page-header">
        <h2 class="page-title">Select Report Type</h2>
        <p class="page-subtitle">Choose a report type to view detailed information</p>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Sales Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <a href="{{ route_include_subdirectory('reports.sales_summary') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">📊</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Sales Summary</h3>
                <p style="color: #666; font-size: 14px;">Overview of total sales for a date range</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.sales_invoice_detail') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">📄</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Sales Invoice Detail</h3>
                <p style="color: #666; font-size: 14px;">Detailed list of all sales invoices</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.sales_by_customer') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">👥</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Sales by Customer</h3>
                <p style="color: #666; font-size: 14px;">Sales performance by customer</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.sales_by_product') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Sales by Product</h3>
                <p style="color: #666; font-size: 14px;">Product-wise sales analysis</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Purchase Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <a href="{{ route_include_subdirectory('reports.purchase_summary') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">📊</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Purchase Summary</h3>
                <p style="color: #666; font-size: 14px;">Overview of total purchases</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.purchase_invoice_detail') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">📄</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Purchase Invoice Detail</h3>
                <p style="color: #666; font-size: 14px;">Detailed list of all purchase invoices</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.purchase_by_supplier') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">🏭</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Purchase by Supplier</h3>
                <p style="color: #666; font-size: 14px;">Purchase analysis by supplier</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Inventory Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <a href="{{ route_include_subdirectory('reports.stock') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Current Stock</h3>
                <p style="color: #666; font-size: 14px;">Real-time inventory status</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.low_stock') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">⚠️</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Low Stock</h3>
                <p style="color: #666; font-size: 14px;">Products below minimum stock level</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.stock_movement') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px;">📈</div>
                <h3 style="color: #6D2D9D; margin-bottom: 10px;">Stock Movement</h3>
                <p style="color: #666; font-size: 14px;">Track inventory changes over time</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Other Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">

        <a href="{{ route_include_subdirectory('reports.sales') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">💰</div>
            <h3 style="color: #6D2D9D; margin-bottom: 10px;">Sales Report</h3>
            <p style="color: #666; font-size: 14px;">View sales invoices and revenue details</p>
        </a>

        <a href="{{ route_include_subdirectory('reports.profit') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">📈</div>
            <h3 style="color: #6D2D9D; margin-bottom: 10px;">Profit Report</h3>
            <p style="color: #666; font-size: 14px;">Analyze profit margins and profitability</p>
        </a>

        <a href="{{ route_include_subdirectory('reports.customer') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">👥</div>
            <h3 style="color: #6D2D9D; margin-bottom: 10px;">Customer Report</h3>
            <p style="color: #666; font-size: 14px;">View customer sales and transaction history</p>
        </a>

        <a href="{{ route_include_subdirectory('reports.supplier') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">🏭</div>
            <h3 style="color: #6D2D9D; margin-bottom: 10px;">Supplier Report</h3>
            <p style="color: #666; font-size: 14px;">View supplier purchases and transactions</p>
        </a>

        <a href="{{ route_include_subdirectory('reports.expense') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">💸</div>
            <h3 style="color: #6D2D9D; margin-bottom: 10px;">Expense Report</h3>
            <p style="color: #666; font-size: 14px;">View expenses by type and date range</p>
        </a>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(109, 45, 157, 0.2);
    }
</style>
@endsection

