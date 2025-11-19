<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Previous Invoices - {{ $customerName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            padding: 15px;
            background: #f5f5f5;
        }
        .header {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header h2 {
            color: #6D2D9D;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .card {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table thead {
            background: #6D2D9D;
            color: white;
        }
        .table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        .table td {
            padding: 8px;
            border-bottom: 1px solid #e1e5e9;
            font-size: 12px;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 4px 12px;
            background: #6D2D9D;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 11px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5a2380;
        }
        .btn-warning {
            background: #ffc107;
            color: #000;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .no-invoices {
            padding: 30px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Previous Invoices</h2>
        <p><strong>Customer:</strong> {{ $customerCode }} - {{ $customerName }}</p>
        <p><strong>Total Invoices:</strong> {{ $invoices->count() }}</p>
    </div>

    <div class="card">
        @if($invoices->count() > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Salesman</th>
                    <th>Remarks</th>
                    <th>Prev Balance</th>
                    <th>Total Amount</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $invoice)
                    @php
                        $totalAmount = $invoice->items->sum('net_amount');
                    @endphp
                    <tr>
                        <td><strong>{{ $invoice->invoice_no }}</strong></td>
                        <td>{{ $invoice->invoice_date }}</td>
                        <td>{{ $invoice->salesman_code }} - {{ $invoice->salesman_name }}</td>
                        <td>{{ $invoice->remarks }}</td>
                        <td class="text-right">{{ number_format($invoice->previous_balance, 2) }}</td>
                        <td class="text-right"><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                        <td class="text-center">
                            <button onclick="window.opener.location.href='{{ route_include_subdirectory('sales_invoices.show', ['sales_invoice' => $invoice->id, 'subdomain' => $subdomain]) }}'; window.close();" class="btn btn-warning">View</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="no-invoices">
                <p>No previous invoices found for this customer.</p>
            </div>
        @endif
    </div>
</body>
</html>

