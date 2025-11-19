@extends('tenant.layouts.admin')

@section('title', 'Stock Report')
@section('page-title', 'Stock Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Stock Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Current inventory levels and stock status</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.stock') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Company</label>
                <select name="company_id" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
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
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Low Stock Only</label>
                <select name="low_stock" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="0" {{ !$lowStock ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $lowStock ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Report Data -->
    @if($products->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Company</th>
                        <th>Current Stock (Box)</th>
                        <th>Current Stock (Pcs)</th>
                        <th>Minimum Stock (Box)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr style="{{ $product->is_low_stock ? 'background: #fff3cd;' : '' }}">
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->company ? $product->company->name : 'N/A' }}</td>
                        <td>{{ number_format($product->current_stock_box, 0) }}</td>
                        <td>{{ number_format($product->current_stock_pcs, 0) }}</td>
                        <td>{{ $product->minimum_stock_box }}</td>
                        <td>
                            @if($product->is_low_stock)
                                <span style="color: #dc3545; font-weight: 600;">Low Stock</span>
                            @else
                                <span style="color: #28a745;">In Stock</span>
                            @endif
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
            <p>No products found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

