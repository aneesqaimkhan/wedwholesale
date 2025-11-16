@extends('tenant.layouts.admin')

@section('title', 'Area Details')
@section('page-title', 'Area Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Area Details</h1>
    <p class="page-subtitle">View area information</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Area Information</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route_include_subdirectory('areas.edit', ['subdomain' => request()->route('subdomain'), 'area' => $area->id]) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route_include_subdirectory('areas.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Area ID:</strong>
                <div style="color: #666;">{{ $area->id }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Name:</strong>
                <div style="color: #666;">{{ $area->name }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Created At:</strong>
                <div style="color: #666;">{{ $area->created_at->format('M d, Y H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Last Updated:</strong>
                <div style="color: #666;">{{ $area->updated_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

