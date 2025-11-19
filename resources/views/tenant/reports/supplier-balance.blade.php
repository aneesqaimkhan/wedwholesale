@extends('tenant.layouts.admin')

@section('title', 'Supplier Balance Report')
@section('page-title', 'Supplier Balance Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Supplier Balance Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Supplier account balances</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.supplier_balance') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Balance Filter</label>
                <select name="balance_filter" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="all" {{ $balanceFilter == 'all' ? 'selected' : '' }}>All</option>
                    <option value="positive" {{ $balanceFilter == 'positive' ? 'selected' : '' }}>Positive Balance</option>
                    <option value="negative" {{ $balanceFilter == 'negative' ? 'selected' : '' }}>Negative Balance</option>
                    <option value="zero" {{ $balanceFilter == 'zero' ? 'selected' : '' }}>Zero Balance</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Report Data -->
    @if(count($balanceData) > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Supplier Code</th>
                        <th>Supplier Name</th>
                        <th>Opening Balance</th>
                        <th>Total Purchases</th>
                        <th>Total Payments</th>
                        <th>Current Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($balanceData as $data)
                    <tr>
                        <td>{{ $data['supplier_code'] }}</td>
                        <td>{{ $data['supplier_name'] }}</td>
                        <td>{{ number_format($data['opening_balance'], 2) }}</td>
                        <td>{{ number_format($data['total_purchases'], 2) }}</td>
                        <td>{{ number_format($data['total_payments'], 2) }}</td>
                        <td style="font-weight: 600; {{ $data['current_balance'] < 0 ? 'color: red;' : ($data['current_balance'] > 0 ? 'color: green;' : '') }}">
                            {{ number_format($data['current_balance'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="font-weight: 600; background: #f8f9fa;">
                        <td colspan="2">Total</td>
                        <td>{{ number_format(array_sum(array_column($balanceData, 'opening_balance')), 2) }}</td>
                        <td>{{ number_format(array_sum(array_column($balanceData, 'total_purchases')), 2) }}</td>
                        <td>{{ number_format(array_sum(array_column($balanceData, 'total_payments')), 2) }}</td>
                        <td>{{ number_format(array_sum(array_column($balanceData, 'current_balance')), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 15px; text-align: right;">
            <button onclick="window.print()" class="btn">Print Report</button>
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p>No supplier balances found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

