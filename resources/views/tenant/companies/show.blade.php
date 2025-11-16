@extends('tenant.layouts.admin')

@section('title', 'View Company')
@section('page-title', 'View Company')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Company Details</h3>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route_include_subdirectory('companies.edit', ['subdomain' => request()->route('subdomain'), 'company' => $company->id]) }}" class="btn btn-warning" style="padding: 6px 16px; font-size: 12px;">Edit</a>
            <a href="{{ route_include_subdirectory('companies.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Back</a>
        </div>
    </div>

    <table class="table" style="font-size: 12px;">
        <tr>
            <th style="font-size: 11px; padding: 8px; width: 150px;">ID</th>
            <td style="padding: 8px;">{{ $company->id }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Name</th>
            <td style="padding: 8px;">{{ $company->name }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Mobile</th>
            <td style="padding: 8px;">{{ $company->mobile }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Address</th>
            <td style="padding: 8px;">{{ $company->address }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Created At</th>
            <td style="padding: 8px;">{{ $company->created_at->format('M d, Y H:i') }}</td>
        </tr>
    </table>
</div>
@endsection

