@extends('tenant.layouts.admin')

@section('title', 'Supplier Report')
@section('page-title', 'Supplier Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Supplier Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Supplier purchases and transactions</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.supplier') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                <label style="font-size: 12px; margin-bottom: 5px;">Supplier</label>
                <select name="supplier_id" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $supplierId == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Report Data -->
    @if(count($supplierData) > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Total Purchases</th>
                        <th>Total Amount</th>
                        <th>Average per Purchase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplierData as $data)
                    <tr>
                        <td>{{ $data['supplier_name'] }}</td>
                        <td>{{ $data['total_purchases'] }}</td>
                        <td style="font-weight: 600;">{{ number_format($data['total_amount'], 2) }}</td>
                        <td>{{ $data['total_purchases'] > 0 ? number_format($data['total_amount'] / $data['total_purchases'], 2) : 0 }}</td>
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
            <p>No supplier data found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

