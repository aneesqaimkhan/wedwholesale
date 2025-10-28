@extends('tenant.layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="page-header">
    <h1 class="page-title">Customers</h1>
    <p class="page-subtitle">Manage your customer database</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Customer List</h3>
        <a href="{{ route('customers.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn">Add New Customer</a>
    </div>

    @if($customers->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->mobile }}</td>
                    <td>{{ Str::limit($customer->address, 50) }}</td>
                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('customers.show', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">View</a>
                        <a href="{{ route('customers.edit', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', ['subdomain' => request()->route('subdomain'), 'customer' => $customer->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $customers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>No customers found. <a href="{{ route('customers.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first customer</a></p>
        </div>
    @endif
</div>
@endsection
