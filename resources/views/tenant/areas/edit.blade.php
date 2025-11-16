@extends('tenant.layouts.admin')

@section('title', 'Edit Area')
@section('page-title', 'Edit Area')

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
    <form method="POST" action="{{ route_include_subdirectory('areas.update', ['subdomain' => request()->route('subdomain'), 'area' => $area->id]) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Area Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $area->name) }}" placeholder="Enter area name" required>
            @error('name')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px;">Update Area</button>
            <a href="{{ route_include_subdirectory('areas.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Cancel</a>
        </div>
    </form>
</div>
@endsection

