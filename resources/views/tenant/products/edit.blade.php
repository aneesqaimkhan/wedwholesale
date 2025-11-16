@extends('tenant.layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<style>
    .compact-form {
        font-size: 13px;
    }
    .compact-form .form-group {
        margin-bottom: 8px;
    }
    .compact-form .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: #666;
        margin-bottom: 2px;
    }
    .compact-form .form-control {
        padding: 5px 8px;
        font-size: 12px;
        height: 30px;
    }
    .compact-form .section-title {
        font-size: 12px;
        font-weight: 600;
        color: #6D2D9D;
        margin: 8px 0 5px 0;
        padding-bottom: 3px;
        border-bottom: 1px solid #e1e5e9;
    }
    .compact-form .grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }
    .compact-form .grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
    .compact-form .error {
        font-size: 11px;
        margin-top: 3px;
    }
</style>

<div class="card compact-form">
    <form method="POST" action="{{ route_include_subdirectory('products.update', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}">
        @csrf
        @method('PUT')
        
        <div class="grid-3">
            <div class="form-group">
                <label for="product_code">Product Code *</label>
                <input type="text" id="product_code" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" placeholder="Enter code" required>
                @error('product_code')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="product_name">Product Name *</label>
                <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" placeholder="Enter name" required>
                @error('product_name')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="packing">Packing</label>
                <input type="text" id="packing" name="packing" class="form-control" value="{{ old('packing', $product->packing) }}" placeholder="Enter packing">
                @error('packing')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="section-title">Quantities & Prices</div>
        <div class="grid-4">
            <div class="form-group">
                <label for="opening_qty_box">Opening Qty (Box)</label>
                <input type="number" id="opening_qty_box" name="opening_qty_box" class="form-control" value="{{ old('opening_qty_box', $product->opening_qty_box ?: '') }}" min="0" placeholder="0">
                @error('opening_qty_box')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="minimum_stock_box">Minimum Stock (Box)</label>
                <input type="number" id="minimum_stock_box" name="minimum_stock_box" class="form-control" value="{{ old('minimum_stock_box', $product->minimum_stock_box ?: '') }}" min="0" placeholder="0">
                @error('minimum_stock_box')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="n_price_box">Normal Price (Box)</label>
                <input type="number" id="n_price_box" name="n_price_box" class="form-control" value="{{ old('n_price_box', $product->n_price_box ?: '') }}" step="0.01" min="0" placeholder="0.00">
                @error('n_price_box')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="t_price_box">Trade Price (Box)</label>
                <input type="number" id="t_price_box" name="t_price_box" class="form-control" value="{{ old('t_price_box', $product->t_price_box ?: '') }}" step="0.01" min="0" placeholder="0.00">
                @error('t_price_box')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="grid-4">
            <div class="form-group">
                <label for="r_price_box">Retail Price (Box)</label>
                <input type="number" id="r_price_box" name="r_price_box" class="form-control" value="{{ old('r_price_box', $product->r_price_box ?: '') }}" step="0.01" min="0" placeholder="0.00">
                @error('r_price_box')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sales_tax">Sales Tax</label>
                <input type="number" id="sales_tax" name="sales_tax" class="form-control" value="{{ old('sales_tax', $product->sales_tax ?: '') }}" step="0.01" min="0" max="100" placeholder="0.00">
                @error('sales_tax')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="rate_in_percent">Rate in Percent (%)</label>
                <input type="number" id="rate_in_percent" name="rate_in_percent" class="form-control" value="{{ old('rate_in_percent', $product->rate_in_percent ?: '') }}" step="0.01" min="0" max="100" placeholder="0.00">
                @error('rate_in_percent')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="default_rate_type">Default Rate Type</label>
                <select id="default_rate_type" name="default_rate_type" class="form-control">
                    <option value="N" {{ old('default_rate_type', $product->default_rate_type) == 'N' ? 'selected' : '' }}>Normal (N)</option>
                    <option value="T" {{ old('default_rate_type', $product->default_rate_type) == 'T' ? 'selected' : '' }}>Trade (T)</option>
                    <option value="R" {{ old('default_rate_type', $product->default_rate_type) == 'R' ? 'selected' : '' }}>Retail (R)</option>
                </select>
                @error('default_rate_type')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="company_id">Company</label>
            <select id="company_id" name="company_id" class="form-control">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px;">Update Product</button>
            <a href="{{ route_include_subdirectory('products.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const retailPriceInput = document.getElementById('r_price_box');
    const tradePriceInput = document.getElementById('t_price_box');
    
    if (retailPriceInput && tradePriceInput) {
        retailPriceInput.addEventListener('input', function() {
            const retailPrice = parseFloat(this.value);
            
            if (!isNaN(retailPrice) && retailPrice > 0) {
                // Calculate Trade Price as 15% decrease from Retail Price
                const tradePrice = retailPrice * 0.85;
                tradePriceInput.value = tradePrice.toFixed(2);
            } else if (this.value === '' || this.value === null) {
                // Clear trade price if retail price is cleared
                tradePriceInput.value = '';
            }
        });
    }
});
</script>
@endsection
