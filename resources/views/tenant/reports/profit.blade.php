@extends('tenant.layouts.admin')

@section('title', 'Profit Report')
@section('page-title', 'Profit Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Profit Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Analyze profit margins and profitability</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.profit') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                        <option value="{{ $product->product_code }}" {{ $productCode == $product->product_code ? 'selected' : '' }}>{{ $product->product_code }} - {{ $product->product_name }}</option>
                    @endforeach
                </select>
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
                <div style="font-size: 12px; opacity: 0.9;">Total Sales</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalSales, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Cost</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalCost, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Profit</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalProfit, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Profit Margin</div>
                <div style="font-size: 20px; font-weight: 600;">{{ $totalSales > 0 ? number_format(($totalProfit / $totalSales) * 100, 2) : 0 }}%</div>
            </div>
        </div>
    </div>

    <!-- Report Data -->
    @if(count($profitData) > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Sold (Box)</th>
                        <th>Sold (Pcs)</th>
                        <th>Sales Amount</th>
                        <th>Cost Amount</th>
                        <th>Profit</th>
                        <th>Profit %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profitData as $data)
                    <tr>
                        <td>{{ $data['product_code'] }}</td>
                        <td>{{ $data['product_name'] }}</td>
                        <td>{{ number_format($data['total_sold_boxes'], 0) }}</td>
                        <td>{{ number_format($data['total_sold_pcs'], 0) }}</td>
                        <td>{{ number_format($data['total_sales_amount'], 2) }}</td>
                        <td>{{ number_format($data['total_cost_amount'], 2) }}</td>
                        <td style="font-weight: 600; color: {{ $data['profit'] >= 0 ? '#28a745' : '#dc3545' }};">{{ number_format($data['profit'], 2) }}</td>
                        <td style="font-weight: 600; color: {{ $data['profit'] >= 0 ? '#28a745' : '#dc3545' }};">{{ number_format($data['profit_percentage'], 2) }}%</td>
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
            <p>No profit data found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

