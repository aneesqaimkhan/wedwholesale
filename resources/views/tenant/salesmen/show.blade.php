@extends('tenant.layouts.admin')

@section('title', 'Salesman Details')
@section('page-title', 'Salesman Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Salesman Details</h1>
    <p class="page-subtitle">View salesman information</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Salesman Information</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('salesmen.edit', ['subdomain' => request()->route('subdomain'), 'salesman' => $salesman->id]) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('salesmen.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Salesman ID:</strong>
                <div style="color: #666;">{{ $salesman->id }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Name:</strong>
                <div style="color: #666;">{{ $salesman->name }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Mobile:</strong>
                <div style="color: #666;">{{ $salesman->mobile }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom: 15px;">
                <strong>Address:</strong>
                <div style="color: #666;">{{ $salesman->address ?: 'Not provided' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Created At:</strong>
                <div style="color: #666;">{{ $salesman->created_at->format('M d, Y H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Last Updated:</strong>
                <div style="color: #666;">{{ $salesman->updated_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
