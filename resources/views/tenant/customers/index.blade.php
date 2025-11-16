@extends('tenant.layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Customer List</h3>
        <a href="{{ route_include_subdirectory('customers.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Customer</a>
    </div>

    @if($customers->count() > 0)
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
                @foreach($customers as $customer)
                <tr>
                    <td style="padding: 8px;">{{ $customer->id }}</td>
                    <td style="padding: 8px;">{{ $customer->name }}</td>
                    <td style="padding: 8px;">{{ $customer->mobile }}</td>
                    <td style="padding: 8px;">{{ Str::limit($customer->address, 50) }}</td>
                    <td style="padding: 8px;">{{ $customer->area ? $customer->area->name : '-' }}</td>
                    <td style="padding: 8px;">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('customers.show', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        <a href="{{ route_include_subdirectory('customers.edit', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        <form method="POST" action="{{ route_include_subdirectory('customers.destroy', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer?')">
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
            {{ $customers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No customers found. <a href="{{ route_include_subdirectory('customers.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first customer</a></p>
        </div>
    @endif
</div>
@endsection
