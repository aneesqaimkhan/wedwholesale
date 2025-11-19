@extends('tenant.layouts.admin')

@section('title', 'Purchase by Supplier Report')
@section('page-title', 'Purchase by Supplier Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Purchase by Supplier Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Purchase analysis by supplier</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.purchase_by_supplier') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                        <th>Supplier Code</th>
                        <th>Supplier Name</th>
                        <th>Number of Invoices</th>
                        <th>Total Purchase Amount</th>
                        <th>Total Quantity (Boxes)</th>
                        <th>Total Quantity (Pcs)</th>
                        <th>Outstanding Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplierData as $data)
                    <tr>
                        <td>{{ $data['supplier_code'] }}</td>
                        <td style="font-weight: 600;">{{ $data['supplier_name'] }}</td>
                        <td>{{ number_format($data['number_of_invoices']) }}</td>
                        <td style="font-weight: 600;">{{ number_format($data['total_purchase_amount'], 2) }}</td>
                        <td>{{ number_format($data['total_quantity_box']) }}</td>
                        <td>{{ number_format($data['total_quantity_pcs']) }}</td>
                        <td style="font-weight: 600; color: {{ $data['outstanding_balance'] > 0 ? '#d32f2f' : '#2e7d32' }};">
                            {{ number_format($data['outstanding_balance'], 2) }}
                        </td>
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
            <p>No purchase data found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

