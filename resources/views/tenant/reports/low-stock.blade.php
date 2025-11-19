@extends('tenant.layouts.admin')

@section('title', 'Low Stock Report')
@section('page-title', 'Low Stock Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Low Stock Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Identify products below minimum stock level</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.low_stock') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Supplier</label>
                <select name="supplier_id" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $supplierId == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Company</label>
                <select name="company_id" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Report Data -->
    @if(count($lowStockProducts) > 0)
        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 15px; border-left: 4px solid #ffc107;">
            <strong style="color: #856404;">⚠️ Alert: {{ count($lowStockProducts) }} product(s) below minimum stock level</strong>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Current Stock (Box)</th>
                        <th>Current Stock (Pcs)</th>
                        <th>Minimum Stock (Box)</th>
                        <th>Minimum Stock (Pcs)</th>
                        <th>Stock Deficit</th>
                        <th>Supplier</th>
                        <th>Company</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                    <tr style="background: #fff3cd;">
                        <td>{{ $product['product_code'] }}</td>
                        <td style="font-weight: 600;">{{ $product['product_name'] }}</td>
                        <td style="color: #dc3545; font-weight: 600;">{{ number_format($product['current_stock_box'], 0) }}</td>
                        <td style="color: #dc3545; font-weight: 600;">{{ number_format($product['current_stock_pcs'], 0) }}</td>
                        <td>{{ number_format($product['minimum_stock_box'], 0) }}</td>
                        <td>{{ number_format($product['minimum_stock_pcs'], 0) }}</td>
                        <td style="color: #dc3545; font-weight: 600;">{{ number_format($product['stock_deficit'], 0) }} boxes</td>
                        <td>{{ $product['supplier'] }}</td>
                        <td>{{ $product['company'] }}</td>
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
            <p>✅ All products are above minimum stock level.</p>
        </div>
    @endif
</div>
@endsection

