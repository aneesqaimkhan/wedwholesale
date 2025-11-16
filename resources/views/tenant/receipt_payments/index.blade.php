@extends('tenant.layouts.admin')

@section('title', 'Receipt Payments')
@section('page-title', 'Receipt Payments')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Receipt/Payment List</h3>
        <a href="{{ route_include_subdirectory('receipt_payments.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Receipt/Payment</a>
    </div>

    @if($payments->count() > 0)
        @foreach($payments as $date => $datePayments)
            <div style="margin-bottom: 25px;">
                <div style="background-color: #6D2D9D; color: white; padding: 10px 12px; font-weight: 600; font-size: 13px; margin-bottom: 8px; border-radius: 4px 4px 0 0;">
                    <span>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                    <span style="float: right; font-weight: 500; font-size: 11px;">{{ $datePayments->count() }} record(s)</span>
                </div>
                
                <table class="table" style="font-size: 12px; margin-bottom: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th style="font-size: 11px; padding: 8px; text-align: left;">ID</th>
                            <th style="font-size: 11px; padding: 8px; text-align: left;">Payment From</th>
                            <th style="font-size: 11px; padding: 8px; text-align: left;">Entity</th>
                            <th style="font-size: 11px; padding: 8px; text-align: right;">Receipt</th>
                            <th style="font-size: 11px; padding: 8px; text-align: right;">Payment</th>
                            <th style="font-size: 11px; padding: 8px; text-align: left;">Remarks</th>
                            <th style="font-size: 11px; padding: 8px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datePayments as $payment)
                        <tr>
                            <td style="padding: 8px; text-align: left;">{{ $payment->id }}</td>
                            <td style="padding: 8px; text-align: left;">{{ ucfirst($payment->payment_from) }}</td>
                            <td style="padding: 8px; text-align: left;">
                                @if($payment->payment_from === 'customer')
                                    {{ $payment->entity_code }} - {{ $payment->entity_name }}
                                @elseif($payment->supplier_code)
                                    {{ $payment->supplier_code }} - {{ $payment->supplier_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="padding: 8px; text-align: right; color: #28a745; font-weight: 500;">
                                @if($payment->receipt > 0)
                                    {{ number_format($payment->receipt, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 8px; text-align: right; color: #dc3545; font-weight: 500;">
                                @if($payment->payment > 0)
                                    {{ number_format($payment->payment, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 8px; text-align: left;">{{ Str::limit($payment->remarks, 50) }}</td>
                            <td style="padding: 8px; text-align: center;">
                                <a href="{{ route_include_subdirectory('receipt_payments.show', ['subdomain' => request()->route('subdomain'), 'receipt_payment' => $payment->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                                <a href="{{ route_include_subdirectory('receipt_payments.create', ['subdomain' => request()->route('subdomain')]) }}?date={{ $date }}" class="btn btn-primary" style="padding: 4px 8px; font-size: 11px; background: #6D2D9D; border-color: #6D2D9D;">Edit</a>
                                <form method="POST" action="{{ route_include_subdirectory('receipt_payments.destroy', ['subdomain' => request()->route('subdomain'), 'receipt_payment' => $payment->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this receipt/payment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f8f9fa; font-weight: 600;">
                            <td colspan="3" style="padding: 8px; text-align: right;">Total for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}:</td>
                            <td style="padding: 8px; text-align: right; color: #28a745;">
                                {{ number_format($datePayments->sum('receipt'), 2) }}
                            </td>
                            <td style="padding: 8px; text-align: right; color: #dc3545;">
                                {{ number_format($datePayments->sum('payment'), 2) }}
                            </td>
                            <td style="padding: 8px; text-align: right; color: #6D2D9D;">
                                Net: {{ number_format($datePayments->sum('receipt') - $datePayments->sum('payment'), 2) }}
                            </td>
                            <td style="padding: 8px; text-align: center;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No receipts/payments found. <a href="{{ route_include_subdirectory('receipt_payments.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first receipt/payment</a></p>
        </div>
    @endif
</div>
@endsection

