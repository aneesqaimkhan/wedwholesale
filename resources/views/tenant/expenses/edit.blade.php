@extends('tenant.layouts.admin')

@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<style>
    .compact-form {
        font-size: 13px;
    }
    .compact-form .form-group {
        margin-bottom: 8px;
    }
    .compact-form .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: #666;
        margin-bottom: 2px;
    }
    .compact-form .form-control {
        padding: 5px 8px;
        font-size: 12px;
        height: 30px;
    }
    .compact-form textarea.form-control {
        height: auto;
        min-height: 60px;
    }
    .compact-form .error {
        font-size: 11px;
        margin-top: 3px;
    }
</style>

<div class="card compact-form">
    <form method="POST" action="{{ route_include_subdirectory('expenses.update', ['subdomain' => request()->route('subdomain'), 'expense' => $expense->id]) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="expense_type_id">Expense Type *</label>
            <select id="expense_type_id" name="expense_type_id" class="form-control" required>
                <option value="">Select Expense Type</option>
                @foreach($expenseTypes as $expenseType)
                    <option value="{{ $expenseType->id }}" {{ old('expense_type_id', $expense->expense_type_id) == $expenseType->id ? 'selected' : '' }}>
                        {{ $expenseType->name }}
                    </option>
                @endforeach
            </select>
            @error('expense_type_id')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Date *</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required>
            @error('date')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0" placeholder="0.00" required>
            @error('amount')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" class="form-control" placeholder="Enter remarks (optional)">{{ old('remarks', $expense->remarks) }}</textarea>
            @error('remarks')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px;">Update Expense</button>
            <a href="{{ route_include_subdirectory('expenses.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Cancel</a>
        </div>
    </form>
</div>
@endsection

