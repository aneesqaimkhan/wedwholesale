@extends('tenant.layouts.admin')

@section('title', 'Expense Report')
@section('page-title', 'Expense Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Expense Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Expenses by type and date range</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.expense') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
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
                <label style="font-size: 12px; margin-bottom: 5px;">Expense Type</label>
                <select name="expense_type_id" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All Types</option>
                    @foreach($expenseTypes as $type)
                        <option value="{{ $type->id }}" {{ $expenseTypeId == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
                <div style="font-size: 12px; opacity: 0.9;">Total Expenses</div>
                <div style="font-size: 20px; font-weight: 600;">{{ number_format($totalAmount, 2) }}</div>
            </div>
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Total Records</div>
                <div style="font-size: 20px; font-weight: 600;">{{ $expenses->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Expense by Type Summary -->
    @if(count($expenseByType) > 0)
        <div class="card" style="margin-bottom: 15px;">
            <h4 style="font-size: 14px; margin-bottom: 10px;">Expenses by Type</h4>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Expense Type</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenseByType as $type => $amount)
                        <tr>
                            <td>{{ $type }}</td>
                            <td style="font-weight: 600;">{{ number_format($amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Report Data -->
    @if($expenses->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Expense Type</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->date->format('Y-m-d') }}</td>
                        <td>{{ $expense->expenseType ? $expense->expenseType->name : 'Unknown' }}</td>
                        <td style="font-weight: 600;">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->remarks ?? 'N/A' }}</td>
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
            <p>No expenses found matching the criteria.</p>
        </div>
    @endif
</div>
@endsection

