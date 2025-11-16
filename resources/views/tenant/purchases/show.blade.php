@extends('tenant.layouts.admin')

@section('title', 'Purchase #'.$purchase->invoice_no)
@section('page-title', 'Purchase #'.$purchase->invoice_no)

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between;">
            <div>
                <div><strong>Date:</strong> {{ $purchase->invoice_date }}</div>
                <div><strong>Company:</strong> {{ $purchase->company_code }} - {{ $purchase->company_name }}</div>
                <div><strong>Address:</strong> {{ $purchase->address }}</div>
            </div>
            <div>
                <div><strong>Prev Balance:</strong> {{ number_format($purchase->previous_balance, 2) }}</div>
                <div><strong>Remarks:</strong> {{ $purchase->remarks }}</div>
            </div>
        </div>

        <table class="table" style="margin-top:20px; font-size: 12px;">
            <thead>
            <tr>
                <th style="font-size: 11px; padding: 8px;">Code</th>
                <th style="font-size: 11px; padding: 8px;">Rate Type</th>
                <th style="font-size: 11px; padding: 8px;">Product</th>
                <th style="font-size: 11px; padding: 8px;">Pack</th>
                <th style="font-size: 11px; padding: 8px;">Box</th>
                <th style="font-size: 11px; padding: 8px;">Rate</th>
                <th style="font-size: 11px; padding: 8px;">B/Box</th>
                <th style="font-size: 11px; padding: 8px;">STX</th>
                <th style="font-size: 11px; padding: 8px;">Disc</th>
                <th style="font-size: 11px; padding: 8px;">Net</th>
            </tr>
            </thead>
            <tbody>
            @php $total = 0; @endphp
            @foreach($purchase->items as $it)
                @php $total += (float)$it->net_amount; @endphp
                <tr>
                    <td style="padding: 8px;">{{ $it->product_code }}</td>
                    <td style="padding: 8px;">{{ $it->rate_type ?? 'N' }}</td>
                    <td style="padding: 8px;">{{ $it->product_name }}</td>
                    <td style="padding: 8px;">{{ $it->pack }}</td>
                    <td style="padding: 8px;">{{ $it->box }}</td>
                    <td style="padding: 8px;">{{ number_format($it->rate, 2) }}</td>
                    <td style="padding: 8px;">{{ number_format($it->b_per_box, 2) }}</td>
                    <td style="padding: 8px;">{{ number_format($it->stx, 2) }}</td>
                    <td style="padding: 8px;">{{ number_format($it->discount, 2) }}</td>
                    <td style="padding: 8px;">{{ number_format($it->net_amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="9" class="text-right" style="padding: 8px;">Total</th>
                <th style="padding: 8px;">{{ number_format($total, 2) }}</th>
            </tr>
            </tfoot>
        </table>

        <div class="mt-3 no-print">
            <a href="{{ route_include_subdirectory('purchases.index') }}" class="btn">Back</a>
        </div>
    </div>
@endsection

