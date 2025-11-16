@extends('tenant.layouts.admin')

@section('title', 'Companies')
@section('page-title', 'Companies')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Company List</h3>
        <a href="{{ route_include_subdirectory('companies.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Company</a>
    </div>

    @if($companies->count() > 0)
        <table class="table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; padding: 8px;">ID</th>
                    <th style="font-size: 11px; padding: 8px;">Name</th>
                    <th style="font-size: 11px; padding: 8px;">Mobile</th>
                    <th style="font-size: 11px; padding: 8px;">Address</th>
                    <th style="font-size: 11px; padding: 8px;">Created At</th>
                    <th style="font-size: 11px; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $company)
                <tr>
                    <td style="padding: 8px;">{{ $company->id }}</td>
                    <td style="padding: 8px;">{{ $company->name }}</td>
                    <td style="padding: 8px;">{{ $company->mobile }}</td>
                    <td style="padding: 8px;">{{ Str::limit($company->address, 50) }}</td>
                    <td style="padding: 8px;">{{ $company->created_at->format('M d, Y') }}</td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('companies.show', ['subdomain' => request()->route('subdomain'), 'company' => $company->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        <a href="{{ route_include_subdirectory('companies.edit', ['subdomain' => request()->route('subdomain'), 'company' => $company->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        <form method="POST" action="{{ route_include_subdirectory('companies.destroy', ['subdomain' => request()->route('subdomain'), 'company' => $company->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this company?')">
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
            {{ $companies->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No companies found. <a href="{{ route_include_subdirectory('companies.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first company</a></p>
        </div>
    @endif
</div>
@endsection

