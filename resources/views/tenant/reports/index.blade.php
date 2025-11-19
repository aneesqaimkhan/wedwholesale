@extends('tenant.layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="card">
    <div class="page-header">
        <h2 class="page-title">Select Report Type</h2>
        <p class="page-subtitle">Choose a report type to view detailed information</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <a href="{{ route_include_subdirectory('reports.stock') }}" class="card" style="text-decoration: none; padding: 25px; text-align: center; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;">
            <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
            <h3 style="color: #6D2D9D; margin-bottom: 10px;">Stock Report</h3>
            <p style="color: #666; font-size: 14px;">View current inventory levels and stock status</p>
        </a>

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

