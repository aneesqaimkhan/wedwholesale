@extends('tenant.layouts.admin')

@section('title', 'Expense Type Details')
@section('page-title', 'Expense Type Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Expense Type Details</h1>
    <p class="page-subtitle">View expense type information</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Expense Type Information</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route_include_subdirectory('expense_types.edit', ['subdomain' => request()->route('subdomain'), 'expense_type' => $expenseType->id]) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route_include_subdirectory('expense_types.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Expense Type ID:</strong>
                <div style="color: #666;">{{ $expenseType->id }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Name:</strong>
                <div style="color: #666;">{{ $expenseType->name }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Created At:</strong>
                <div style="color: #666;">{{ $expenseType->created_at->format('M d, Y H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Last Updated:</strong>
                <div style="color: #666;">{{ $expenseType->updated_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

