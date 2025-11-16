@extends('tenant.layouts.admin')

@section('title', 'Supplier Details')
@section('page-title', 'Supplier Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Supplier Details</h1>
    <p class="page-subtitle">View supplier information</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Supplier Information</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route_include_subdirectory('suppliers.edit', ['subdomain' => request()->route('subdomain'), 'supplier' => $supplier->id]) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route_include_subdirectory('suppliers.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Supplier ID:</strong>
                <div style="color: #666;">{{ $supplier->id }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Name:</strong>
                <div style="color: #666;">{{ $supplier->name }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Mobile:</strong>
                <div style="color: #666;">{{ $supplier->mobile }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Address:</strong>
                <div style="color: #666;">{{ $supplier->address ?: 'Not provided' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Area:</strong>
                <div style="color: #666;">{{ $supplier->area ? $supplier->area->name : 'Not assigned' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Created At:</strong>
                <div style="color: #666;">{{ $supplier->created_at->format('M d, Y H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Last Updated:</strong>
                <div style="color: #666;">{{ $supplier->updated_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

