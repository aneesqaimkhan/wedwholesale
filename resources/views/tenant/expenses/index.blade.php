@extends('tenant.layouts.admin')

@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Expense List</h3>
        <a href="{{ route_include_subdirectory('expenses.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Expense</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 12px; font-size: 12px;">
            {{ session('success') }}
        </div>
    @endif

    @if($expenses->count() > 0)
        <table class="table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; padding: 8px;">ID</th>
                    <th style="font-size: 11px; padding: 8px;">Expense Type</th>
                    <th style="font-size: 11px; padding: 8px;">Date</th>
                    <th style="font-size: 11px; padding: 8px;">Amount</th>
                    <th style="font-size: 11px; padding: 8px;">Remarks</th>
                    <th style="font-size: 11px; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td style="padding: 8px;">{{ $expense->id }}</td>
                    <td style="padding: 8px;">{{ $expense->expenseType->name ?? 'N/A' }}</td>
                    <td style="padding: 8px;">{{ $expense->date->format('M d, Y') }}</td>
                    <td style="padding: 8px;">{{ number_format($expense->amount, 2) }}</td>
                    <td style="padding: 8px;">{{ Str::limit($expense->remarks, 30) }}</td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('expenses.show', ['subdomain' => request()->route('subdomain'), 'expense' => $expense->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        <a href="{{ route_include_subdirectory('expenses.edit', ['subdomain' => request()->route('subdomain'), 'expense' => $expense->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        <form method="POST" action="{{ route_include_subdirectory('expenses.destroy', ['subdomain' => request()->route('subdomain'), 'expense' => $expense->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this expense?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px; font-size: 12px;">
            {{ $expenses->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No expenses found. <a href="{{ route_include_subdirectory('expenses.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first expense</a></p>
        </div>
    @endif
</div>
@endsection

