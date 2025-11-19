@extends('tenant.layouts.admin')

@section('title', 'Purchase Invoice Detail Report')
@section('page-title', 'Purchase Invoice Detail Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Purchase Invoice Detail Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Detailed list of all purchase invoices</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.purchase_invoice_detail') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control" style="font-size: 12px; padding: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control" style="font-size: 12px; padding: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Supplier/Company</label>
                <select name="company_code" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Suppliers</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $companyCode == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Invoice Number</label>
                <input type="text" name="invoice_no" value="{{ $invoiceNo }}" class="form-control" style="font-size: 12px; padding: 8px;" placeholder="Search invoice...">
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Report Data -->
    @if($purchases->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Supplier/Company</th>
                        <th>Previous Balance</th>
                        <th>Purchase Amount</th>
                        <th>Current Balance</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->invoice_date }}</td>
                        <td>{{ $purchase->company_name }}</td>
                        <td>{{ number_format($purchase->previous_balance, 2) }}</td>
                        <td style="font-weight: 600;">{{ number_format($purchase->total_amount, 2) }}</td>
                        <td style="font-weight: 600;">{{ number_format($purchase->current_balance, 2) }}</td>
                        <td>{{ $purchase->items->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 15px; text-align: right;">
            <button onclick="window.print()" class="btn">Print Report</button>
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p>No purchase invoices found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

