@extends('tenant.layouts.admin')

@section('title', 'List Status Manual - Products')
@section('page-title', 'List Status Manual - Products')

@section('content')
<style>
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .view-info {
        font-size: 13px;
        color: #666;
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 5px;
    }
    .btn-back {
        background: #6c757d;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 12px;
        transition: background 0.3s;
    }
    .btn-back:hover {
        background: #5a6268;
    }
    .products-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background: white;
        font-size: 12px;
    }
    .products-table th {
        background: #6D2D9D;
        color: white;
        padding: 10px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        border-bottom: 2px solid #5a2470;
    }
    .products-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #e1e5e9;
        color: #333;
    }
    .products-table tbody tr:hover {
        background: #f8f9fa;
    }
    .products-table tbody tr:last-child td {
        border-bottom: none;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
        font-size: 13px;
    }
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
</style>

<div class="card">
    <div class="header-actions">
        <div class="view-info">
            <strong>View:</strong> {{ $companyName }}
        </div>
        <a href="{{ route_include_subdirectory('list_status_manual.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn-back">
            ← Back to Options
        </a>
    </div>

    @if($products->count() > 0)
        <div style="overflow-x: auto;">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Packing</th>
                        <th class="text-right">Sale Rate</th>
                        <th class="text-center">QTY</th>
                        <th class="text-right">Offer</th>
                        <th class="text-right">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        @php
                            // Determine sale rate based on default_rate_type
                            $saleRate = 0;
                            $rateType = $product->default_rate_type ?? 'N';
                            if ($rateType == 'N') {
                                $saleRate = $product->n_price_box ?? 0;
                            } elseif ($rateType == 'T') {
                                $saleRate = $product->t_price_box ?? 0;
                            } elseif ($rateType == 'R') {
                                $saleRate = $product->r_price_box ?? 0;
                            }
                        @endphp
                        <tr>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->packing ?? '-' }}</td>
                            <td class="text-right">{{ number_format($saleRate, 2) }}</td>
                            <td class="text-center">{{ ($product->opening_qty_box ?? 0) . ' / ' . ($product->opening_qty_pcs ?? 0) }}</td>
                            <td class="text-right">{{ number_format($product->sales_tax ?? 0, 2) }}</td>
                            <td class="text-right">{{ number_format($product->rate_in_percent ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 15px; font-size: 12px; color: #666;">
            Total Products: <strong>{{ $products->count() }}</strong>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <p>No products found for <strong>{{ $companyName }}</strong></p>
            <a href="{{ route_include_subdirectory('list_status_manual.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn-back" style="display: inline-block; margin-top: 15px;">
                ← Back to Options
            </a>
        </div>
    @endif
</div>
@endsection

