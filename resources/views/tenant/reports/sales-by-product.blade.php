@extends('tenant.layouts.admin')

@section('title', 'Sales by Product Report')
@section('page-title', 'Sales by Product Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Sales by Product Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Product-wise sales analysis</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.sales_by_product') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                    @foreach($products as $product)
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
    @if(count($productData) > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Total Quantity (Boxes)</th>
                        <th>Total Quantity (Pcs)</th>
                        <th>Total Sales Amount</th>
                        <th>Number of Invoices</th>
                        <th>Average Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productData as $data)
                    <tr>
                        <td>{{ $data['product_code'] }}</td>
                        <td style="font-weight: 600;">{{ $data['product_name'] }}</td>
                        <td>{{ number_format($data['total_quantity_box']) }}</td>
                        <td>{{ number_format($data['total_quantity_pcs']) }}</td>
                        <td style="font-weight: 600;">{{ number_format($data['total_sales_amount'], 2) }}</td>
                        <td>{{ number_format($data['number_of_invoices']) }}</td>
                        <td>{{ number_format($data['average_selling_price'], 2) }}</td>
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
            <p>No sales data found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

