@extends('tenant.layouts.admin')

@section('title', 'Invoice #'.$invoice->invoice_no)
@section('page-title', 'Invoice #'.$invoice->invoice_no)

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between;">
            <div>
                <div><strong>Date:</strong> {{ $invoice->invoice_date }}</div>
                <div><strong>Customer:</strong> {{ $invoice->customer_code }} - {{ $invoice->customer_name }}</div>
                <div><strong>Address:</strong> {{ $invoice->address }}</div>
            </div>
            <div>
                <div><strong>Salesman:</strong> {{ $invoice->salesman_code }} - {{ $invoice->salesman_name }}</div>
                <div><strong>Prev Balance:</strong> {{ number_format($invoice->previous_balance, 2) }}</div>
            </div>
        </div>

        <table class="table" style="margin-top:20px;">
            <thead>
            <tr>
                <th>Product</th>
                <th>Pack</th>
                <th>Box</th>
                <th>Pcs</th>
                <th>Rate</th>
                <th>B/Box</th>
                <th>STX</th>
                <th>Disc</th>
                <th>Net</th>
            </tr>
            </thead>
            <tbody>
            @php $total = 0; @endphp
            @foreach($invoice->items as $it)
                @php $total += (float)$it->net_amount; @endphp
                <tr>
                    <td>{{ $it->product_code }} - {{ $it->product_name }}</td>
                    <td>{{ $it->pack }}</td>
                    <td>{{ $it->box }}</td>
                    <td>{{ $it->pcs }}</td>
                    <td>{{ number_format($it->rate, 2) }}</td>
                    <td>{{ number_format($it->b_per_box, 2) }}</td>
                    <td>{{ number_format($it->stx, 2) }}</td>
                    <td>{{ number_format($it->discount, 2) }}</td>
                    <td>{{ number_format($it->net_amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="8" class="text-right">Total</th>
                <th>{{ number_format($total, 2) }}</th>
            </tr>
            </tfoot>
        </table>

        <div class="mt-3">
            <a href="{{ route_include_subdirectory('sales_invoices.index') }}" class="btn">Back</a>
        </div>
    </div>
@endsection


