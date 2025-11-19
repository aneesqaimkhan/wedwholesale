@extends('tenant.layouts.admin')

@section('title', 'Supplier Purchase History')
@section('page-title', 'Supplier Purchase History')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Supplier Purchase History</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Individual supplier transaction history</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.supplier_purchase_history') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Supplier</label>
                <select name="supplier_id" class="form-control" style="font-size: 12px; padding: 8px;" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->id }}" {{ $supplierId == $supp->id ? 'selected' : '' }}>{{ $supp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control" style="font-size: 12px; padding: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control" style="font-size: 12px; padding: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    @if($supplier)
        <!-- Supplier Info -->
        <div style="background: #6D2D9D; color: white; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
            <div>
                <div style="font-size: 12px; opacity: 0.9;">Supplier</div>
                <div style="font-size: 18px; font-weight: 600;">{{ $supplier->name }}</div>
                <div style="font-size: 12px; margin-top: 5px;">Code: {{ $supplier->id }} | Mobile: {{ $supplier->mobile ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Report Data -->
        @if(count($purchaseData) > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Purchase Amount</th>
                            <th>Payment Made</th>
                            <th>Outstanding Balance</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseData as $data)
                        <tr>
                            <td>{{ $data['purchase']->invoice_no }}</td>
                            <td>{{ $data['purchase']->invoice_date }}</td>
                            <td>{{ number_format($data['purchase_balance'], 2) }}</td>
                            <td>{{ number_format($data['payment_made'], 2) }}</td>
                            <td style="font-weight: 600;">{{ number_format($data['outstanding_balance'], 2) }}</td>
                            <td>
                                <details>
                                    <summary style="cursor: pointer; color: #6D2D9D;">View Items ({{ $data['purchase']->items->count() }})</summary>
                                    <table class="table" style="margin-top: 10px; font-size: 11px;">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Box</th>
                                                <th>Pcs</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['purchase']->items as $item)
                                            <tr>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->box }}</td>
                                                <td>{{ $item->pcs }}</td>
                                                <td>{{ number_format($item->rate, 2) }}</td>
                                                <td>{{ number_format($item->net_amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </details>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 15px; text-align: right;">
                <button onclick="window.print()" class="btn">Print Report</button>
            </div>
        @else
            <div style="text-align: center; padding: 30px; color: #666;">
                <p>No purchases found for this supplier in the selected date range.</p>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p>Please select a supplier to view purchase history.</p>
        </div>
    @endif
</div>
@endsection

