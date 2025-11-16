@extends('tenant.layouts.admin')

@section('title', 'Add Expense Type')
@section('page-title', 'Add Expense Type')

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
    .compact-form .error {
        font-size: 11px;
        margin-top: 3px;
    }
</style>

<div class="card compact-form">
    <form method="POST" action="{{ route_include_subdirectory('expense_types.store', ['subdomain' => request()->route('subdomain')]) }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Expense Type Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter expense type name" required>
            @error('name')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px;">Create Expense Type</button>
            <a href="{{ route_include_subdirectory('expense_types.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Cancel</a>
        </div>
    </form>
</div>
@endsection

