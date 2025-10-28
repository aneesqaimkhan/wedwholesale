@extends('tenant.layouts.admin')

@section('title', 'Add Salesman')
@section('page-title', 'Add Salesman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Salesman</h1>
    <p class="page-subtitle">Create a new salesman record</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('salesmen.store', ['subdomain' => request()->route('subdomain')]) }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Salesman Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile') }}" required>
            @error('mobile')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" class="form-control" rows="4">{{ old('address') }}</textarea>
            @error('address')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn">Create Salesman</button>
            <a href="{{ route('salesmen.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Cancel</a>
        </div>
    </form>
</div>
@endsection
