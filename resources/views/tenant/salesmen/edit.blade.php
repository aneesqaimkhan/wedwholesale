@extends('tenant.layouts.admin')

@section('title', 'Edit Salesman')
@section('page-title', 'Edit Salesman')

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
        min-height: 55px;
    }
    .compact-form .section-title {
        font-size: 12px;
        font-weight: 600;
        color: #6D2D9D;
        margin: 8px 0 5px 0;
        padding-bottom: 3px;
        border-bottom: 1px solid #e1e5e9;
    }
    .compact-form .grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }
    .compact-form .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .compact-form .error {
        font-size: 11px;
        margin-top: 3px;
    }
</style>

<div class="card compact-form">
    <form method="POST" action="{{ route_include_subdirectory('salesmen.update', ['subdomain' => request()->route('subdomain'), 'salesman' => $salesman->id]) }}">
        @csrf
        @method('PUT')
        
        <div class="grid-2">
            <div class="form-group">
                <label for="name">Salesman Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $salesman->name) }}" placeholder="Enter salesman name" required>
                @error('name')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile">Mobile Number *</label>
                <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', $salesman->mobile) }}" placeholder="Enter mobile number" required>
                @error('mobile')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter address">{{ old('address', $salesman->address) }}</textarea>
            @error('address')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px;">Update Salesman</button>
            <a href="{{ route_include_subdirectory('salesmen.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Cancel</a>
        </div>
    </form>
</div>
@endsection
