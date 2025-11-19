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
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <a href="{{ route_include_subdirectory('reports.sales_summary') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📊</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Sales Summary</h3>
                <p style="color: #666; font-size: 11px;">Overview of total sales for a date range</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.sales_invoice_detail') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📄</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Sales Invoice Detail</h3>
                <p style="color: #666; font-size: 11px;">Detailed list of all sales invoices</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.sales_by_customer') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">👥</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Sales by Customer</h3>
                <p style="color: #666; font-size: 11px;">Sales performance by customer</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.sales_by_product') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📦</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Sales by Product</h3>
                <p style="color: #666; font-size: 11px;">Product-wise sales analysis</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Purchase Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <a href="{{ route_include_subdirectory('reports.purchase_summary') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📊</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Purchase Summary</h3>
                <p style="color: #666; font-size: 11px;">Overview of total purchases</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.purchase_invoice_detail') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📄</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Purchase Invoice Detail</h3>
                <p style="color: #666; font-size: 11px;">Detailed list of all purchase invoices</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.purchase_by_supplier') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">🏭</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Purchase by Supplier</h3>
                <p style="color: #666; font-size: 11px;">Purchase analysis by supplier</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Inventory Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
        <a href="{{ route_include_subdirectory('reports.stock') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 32px; margin-bottom: 8px;">📦</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Current Stock</h3>
                <p style="color: #666; font-size: 11px;">Real-time inventory status</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.low_stock') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">⚠️</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Low Stock</h3>
                <p style="color: #666; font-size: 11px;">Products below minimum stock level</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.stock_movement') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📈</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Stock Movement</h3>
                <p style="color: #666; font-size: 11px;">Track inventory changes over time</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Financial Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <a href="{{ route_include_subdirectory('reports.profit_loss') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📊</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Profit & Loss</h3>
                <p style="color: #666; font-size: 11px;">Overall profitability analysis</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.outstanding_receivables') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">💳</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Outstanding Receivables</h3>
                <p style="color: #666; font-size: 11px;">Track money owed by customers</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.outstanding_payables') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">💵</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Outstanding Payables</h3>
                <p style="color: #666; font-size: 11px;">Track money owed to suppliers</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.expense') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">💸</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Expense Report</h3>
                <p style="color: #666; font-size: 11px;">Track all business expenses</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Customer Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <a href="{{ route_include_subdirectory('reports.customer_list') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📋</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Customer List</h3>
                <p style="color: #666; font-size: 11px;">Complete customer directory</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.customer_purchase_history') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">🛒</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Customer Purchase History</h3>
                <p style="color: #666; font-size: 11px;">Individual customer transaction history</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.customer_balance') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">💰</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Customer Balance</h3>
                <p style="color: #666; font-size: 11px;">Customer account balances</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.customer') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">👥</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Customer Report</h3>
                <p style="color: #666; font-size: 11px;">View customer sales and transaction history</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Supplier Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <a href="{{ route_include_subdirectory('reports.supplier_list') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📋</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Supplier List</h3>
                <p style="color: #666; font-size: 11px;">Complete supplier directory</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.supplier_purchase_history') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">🛒</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Supplier Purchase History</h3>
                <p style="color: #666; font-size: 11px;">Individual supplier transaction history</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.supplier_balance') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">💰</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Supplier Balance</h3>
                <p style="color: #666; font-size: 11px;">Supplier account balances</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.supplier') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">🏭</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Supplier Report</h3>
                <p style="color: #666; font-size: 11px;">View supplier purchases and transactions</p>
            </a>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #6D2D9D; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Other Reports</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <a href="{{ route_include_subdirectory('reports.sales') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">💰</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Sales Report</h3>
                <p style="color: #666; font-size: 11px;">View sales invoices and revenue details</p>
            </a>

            <a href="{{ route_include_subdirectory('reports.profit') }}" class="card" style="text-decoration: none; padding: 15px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
                <div style="font-size: 32px; margin-bottom: 8px;">📈</div>
                <h3 style="color: #6D2D9D; margin-bottom: 5px; font-size: 14px;">Profit Report</h3>
                <p style="color: #666; font-size: 11px;">Analyze profit margins and profitability</p>
            </a>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(109, 45, 157, 0.2);
    }
</style>
@endsection

