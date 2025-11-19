@extends('tenant.layouts.admin')

@section('title', 'Profit & Loss Report')
@section('page-title', 'Profit & Loss Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Profit & Loss Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Overall profitability analysis</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.profit_loss') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                <label style="font-size: 12px; margin-bottom: 5px;">Group By</label>
                <select name="group_by" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="none" {{ $groupBy == 'none' ? 'selected' : '' }}>None</option>
                    <option value="daily" {{ $groupBy == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $groupBy == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $groupBy == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $groupBy == 'yearly' ? 'selected' : '' }}>Yearly</option>
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
                <div style="font-size: 12px; opacity: 0.9;">Total Sales Revenue</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalSalesRevenue, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Purchase Cost</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalPurchaseCost, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Gross Profit</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($grossProfit, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Expenses</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalExpenses, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Net Profit</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($netProfit, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Profit Margin</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($profitMargin, 2) }}%</div>
            </div>
        </div>
    </div>

    <!-- Grouped Data -->
    @if(count($groupedData) > 0)
        <div class="card" style="margin-bottom: 15px;">
            <h4 style="font-size: 14px; margin-bottom: 10px;">Grouped Data</h4>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Sales Revenue</th>
                            <th>Purchase Cost</th>
                            <th>Expenses</th>
                            <th>Gross Profit</th>
                            <th>Net Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedData as $data)
                        <tr>
                            <td>{{ $data['period'] }}</td>
                            <td>{{ number_format($data['sales_revenue'], 2) }}</td>
                            <td>{{ number_format($data['purchase_cost'], 2) }}</td>
                            <td>{{ number_format($data['expenses'], 2) }}</td>
                            <td>{{ number_format($data['gross_profit'], 2) }}</td>
                            <td style="font-weight: 600;">{{ number_format($data['net_profit'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div style="margin-top: 15px; text-align: right;">
        <button onclick="window.print()" class="btn">Print Report</button>
    </div>
</div>
@endsection

