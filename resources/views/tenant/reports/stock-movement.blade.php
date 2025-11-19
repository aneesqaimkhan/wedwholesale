@extends('tenant.layouts.admin')

@section('title', 'Stock Movement Report')
@section('page-title', 'Stock Movement Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Stock Movement Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Track inventory changes over time</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.stock_movement') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                <label style="font-size: 12px; margin-bottom: 5px;">Product</label>
                <select name="product_code" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Products</option>
                    @foreach($productsList as $product)
                        <option value="{{ $product->product_code }}" {{ $productCode == $product->product_code ? 'selected' : '' }}>
                            {{ $product->product_code }} - {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Report Data -->
    @if(count($movementData) > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Opening Stock (Box)</th>
                        <th>Opening Stock (Pcs)</th>
                        <th>Purchases (Box)</th>
                        <th>Purchases (Pcs)</th>
                        <th>Purchase Amount</th>
                        <th>Sales (Box)</th>
                        <th>Sales (Pcs)</th>
                        <th>Sales Amount</th>
                        <th>Closing Stock (Box)</th>
                        <th>Closing Stock (Pcs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movementData as $data)
                    <tr>
                        <td>{{ $data['product_code'] }}</td>
                        <td style="font-weight: 600;">{{ $data['product_name'] }}</td>
                        <td>{{ number_format($data['opening_stock_box'], 0) }}</td>
                        <td>{{ number_format($data['opening_stock_pcs'], 0) }}</td>
                        <td style="color: #28a745;">+{{ number_format($data['purchase_quantity_box'], 0) }}</td>
                        <td style="color: #28a745;">+{{ number_format($data['purchase_quantity_pcs'], 0) }}</td>
                        <td style="color: #28a745;">{{ number_format($data['purchase_amount'], 2) }}</td>
                        <td style="color: #dc3545;">-{{ number_format($data['sales_quantity_box'], 0) }}</td>
                        <td style="color: #dc3545;">-{{ number_format($data['sales_quantity_pcs'], 0) }}</td>
                        <td style="color: #dc3545;">{{ number_format($data['sales_amount'], 2) }}</td>
                        <td style="font-weight: 600;">{{ number_format($data['closing_stock_box'], 0) }}</td>
                        <td style="font-weight: 600;">{{ number_format($data['closing_stock_pcs'], 0) }}</td>
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
            <p>No stock movement data found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

