@extends('tenant.layouts.admin')

@section('title', 'View Receipt/Payment')
@section('page-title', 'View Receipt/Payment')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Receipt/Payment Details</h3>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route_include_subdirectory('receipt_payments.edit', ['subdomain' => request()->route('subdomain'), 'receipt_payment' => $receipt_payment->id]) }}" class="btn btn-warning" style="padding: 6px 16px; font-size: 12px;">Edit</a>
            <a href="{{ route_include_subdirectory('receipt_payments.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Back</a>
        </div>
    </div>

    <table class="table" style="font-size: 12px;">
        <tr>
            <th style="font-size: 11px; padding: 8px; width: 150px;">ID</th>
            <td style="padding: 8px;">{{ $receipt_payment->id }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Payment From</th>
            <td style="padding: 8px;">{{ ucfirst($receipt_payment->payment_from) }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Entity Code</th>
            <td style="padding: 8px;">{{ $receipt_payment->entity_code }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Entity Name</th>
            <td style="padding: 8px;">{{ $receipt_payment->entity_name }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Amount</th>
            <td style="padding: 8px;">{{ number_format($receipt_payment->amount, 2) }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Payment Date</th>
            <td style="padding: 8px;">{{ $receipt_payment->payment_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Remarks</th>
            <td style="padding: 8px;">{{ $receipt_payment->remarks }}</td>
        </tr>
        <tr>
            <th style="font-size: 11px; padding: 8px;">Created At</th>
            <td style="padding: 8px;">{{ $receipt_payment->created_at->format('M d, Y H:i') }}</td>
        </tr>
    </table>
</div>
@endsection

