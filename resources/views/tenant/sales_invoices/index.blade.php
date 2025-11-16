@extends('tenant.layouts.admin')

@section('title', 'Sales Invoices')
@section('page-title', 'Sales Invoices')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">All Invoices</h3>
            <a href="{{ route_include_subdirectory('sales_invoices.create') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">+ New Invoice</a>
        </div>

        <table class="table" style="font-size: 12px;">
            <thead>
            <tr>
                <th style="font-size: 11px; padding: 8px;">#</th>
                <th style="font-size: 11px; padding: 8px;">Date</th>
                <th style="font-size: 11px; padding: 8px;">Customer</th>
                <th style="font-size: 11px; padding: 8px;">Salesman</th>
                <th style="font-size: 11px; padding: 8px;">Remarks</th>
                <th style="font-size: 11px; padding: 8px;"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $inv)
                <tr>
                    <td style="padding: 8px;">{{ $inv->invoice_no }}</td>
                    <td style="padding: 8px;">{{ $inv->invoice_date }}</td>
                    <td style="padding: 8px;">{{ $inv->customer_code }} - {{ $inv->customer_name }}</td>
                    <td style="padding: 8px;">{{ $inv->salesman_code }} - {{ $inv->salesman_name }}</td>
                    <td style="padding: 8px;">{{ $inv->remarks }}</td>
                    <td class="text-right" style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('sales_invoices.show', $inv) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding: 8px; font-size: 12px;">No invoices found.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 12px; font-size: 12px;">{{ $invoices->links() }}</div>
    </div>
@endsection


