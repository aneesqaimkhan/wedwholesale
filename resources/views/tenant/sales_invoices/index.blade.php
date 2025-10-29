@extends('tenant.layouts.admin')

@section('title', 'Sales Invoices')
@section('page-title', 'Sales Invoices')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <div>
                <h3 class="mb-0">All Invoices</h3>
                <div class="page-subtitle">Recent first</div>
            </div>
            <a href="{{ route_include_subdirectory('sales_invoices.create') }}" class="btn">+ New Invoice</a>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Salesman</th>
                <th>Remarks</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->invoice_no }}</td>
                    <td>{{ $inv->invoice_date }}</td>
                    <td>{{ $inv->customer_code }} - {{ $inv->customer_name }}</td>
                    <td>{{ $inv->salesman_code }} - {{ $inv->salesman_name }}</td>
                    <td>{{ $inv->remarks }}</td>
                    <td class="text-right">
                        <a href="{{ route_include_subdirectory('sales_invoices.show', $inv) }}" class="btn btn-warning">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No invoices found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $invoices->links() }}</div>
    </div>
@endsection


