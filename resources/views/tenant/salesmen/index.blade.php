@extends('tenant.layouts.admin')

@section('title', 'Salesmen')
@section('page-title', 'Salesmen')

@section('content')
<div class="page-header">
    <h1 class="page-title">Salesmen</h1>
    <p class="page-subtitle">Manage your salesman database</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Salesman List</h3>
        <a href="{{ route('salesmen.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn">Add New Salesman</a>
    </div>

    @if($salesmen->count() > 0)
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
                @foreach($salesmen as $salesman)
                <tr>
                    <td>{{ $salesman->id }}</td>
                    <td>{{ $salesman->name }}</td>
                    <td>{{ $salesman->mobile }}</td>
                    <td>{{ Str::limit($salesman->address, 50) }}</td>
                    <td>{{ $salesman->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('salesmen.show', ['subdomain' => request()->route('subdomain'), 'salesman' => $salesman->id]) }}" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">View</a>
                        <a href="{{ route('salesmen.edit', ['subdomain' => request()->route('subdomain'), 'salesman' => $salesman->id]) }}" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                        <form method="POST" action="{{ route('salesmen.destroy', ['subdomain' => request()->route('subdomain'), 'salesman' => $salesman->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this salesman?')">
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
            {{ $salesmen->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>No salesmen found. <a href="{{ route('salesmen.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first salesman</a></p>
        </div>
    @endif
</div>
@endsection
