@extends('tenant.layouts.admin')

@section('title', 'Expense Details')
@section('page-title', 'Expense Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Expense Details</h1>
    <p class="page-subtitle">View expense information</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Expense Information</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route_include_subdirectory('expenses.edit', ['subdomain' => request()->route('subdomain'), 'expense' => $expense->id]) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route_include_subdirectory('expenses.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Expense ID:</strong>
                <div style="color: #666;">{{ $expense->id }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Expense Type:</strong>
                <div style="color: #666;">{{ $expense->expenseType->name ?? 'N/A' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Date:</strong>
                <div style="color: #666;">{{ $expense->date->format('M d, Y') }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Amount:</strong>
                <div style="color: #666;">{{ number_format($expense->amount, 2) }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Created At:</strong>
                <div style="color: #666;">{{ $expense->created_at->format('M d, Y H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Last Updated:</strong>
                <div style="color: #666;">{{ $expense->updated_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
    
    @if($expense->remarks)
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
        <strong>Remarks:</strong>
        <div style="color: #666; margin-top: 5px;">{{ $expense->remarks }}</div>
    </div>
    @endif
</div>
@endsection

