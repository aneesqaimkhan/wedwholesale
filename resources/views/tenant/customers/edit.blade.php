@extends('tenant.layouts.admin')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Customer</h1>
    <p class="page-subtitle">Update customer information</p>
</div>

<div class="card">
    <form method="POST" action="{{ route_include_subdirectory('customers.update', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Customer Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
            @error('name')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile) }}" required>
            @error('mobile')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" class="form-control" rows="4">{{ old('address', $customer->address) }}</textarea>
            @error('address')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn">Update Customer</button>
            <a href="{{ route_include_subdirectory('customers.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Cancel</a>
        </div>
    </form>
</div>
@endsection
