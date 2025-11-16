@extends('tenant.layouts.admin')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Supplier List</h3>
        <a href="{{ route_include_subdirectory('suppliers.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Supplier</a>
    </div>

    @if($suppliers->count() > 0)
        <table class="table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; padding: 8px;">ID</th>
                    <th style="font-size: 11px; padding: 8px;">Name</th>
                    <th style="font-size: 11px; padding: 8px;">Mobile</th>
                    <th style="font-size: 11px; padding: 8px;">Address</th>
                    <th style="font-size: 11px; padding: 8px;">Area</th>
                    <th style="font-size: 11px; padding: 8px;">Created At</th>
                    <th style="font-size: 11px; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td style="padding: 8px;">{{ $supplier->id }}</td>
                    <td style="padding: 8px;">{{ $supplier->name }}</td>
                    <td style="padding: 8px;">{{ $supplier->mobile }}</td>
                    <td style="padding: 8px;">{{ Str::limit($supplier->address, 50) }}</td>
                    <td style="padding: 8px;">{{ $supplier->area ? $supplier->area->name : '-' }}</td>
                    <td style="padding: 8px;">{{ $supplier->created_at->format('M d, Y') }}</td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('suppliers.show', ['subdomain' => request()->route('subdomain'), 'supplier' => $supplier->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        <a href="{{ route_include_subdirectory('suppliers.edit', ['subdomain' => request()->route('subdomain'), 'supplier' => $supplier->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        <form method="POST" action="{{ route_include_subdirectory('suppliers.destroy', ['subdomain' => request()->route('subdomain'), 'supplier' => $supplier->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px; font-size: 12px;">
            {{ $suppliers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No suppliers found. <a href="{{ route_include_subdirectory('suppliers.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first supplier</a></p>
        </div>
    @endif
</div>
@endsection

