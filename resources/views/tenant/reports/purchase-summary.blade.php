@extends('tenant.layouts.admin')

@section('title', 'Purchase Summary Report')
@section('page-title', 'Purchase Summary Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Purchase Summary Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Overview of total purchases</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.purchase_summary') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control" style="font-size: 12px; padding: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control" style="font-size: 12px; padding: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Summary -->
    <div style="background: #6D2D9D; color: white; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Purchase Invoices</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalInvoices) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Purchase Amount</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalPurchaseAmount, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Quantity (Boxes)</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalQuantityBox) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Quantity (Pcs)</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalQuantityPcs) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Average Purchase Value</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($averagePurchaseValue, 2) }}</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 15px; text-align: right;">
        <button onclick="window.print()" class="btn">Print Report</button>
    </div>
</div>
@endsection

