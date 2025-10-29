@extends('tenant.layouts.admin')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Product</h1>
    <p class="page-subtitle">Create a new product record</p>
</div>

<div class="card">
    <form method="POST" action="{{ route_include_subdirectory('products.store', ['subdomain' => request()->route('subdomain')]) }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="product_code">Product Code *</label>
                <input type="text" id="product_code" name="product_code" class="form-control" value="{{ old('product_code') }}" required>
                @error('product_code')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="product_name">Product Name *</label>
                <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
                @error('product_name')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="pcs_in_box">Pcs in Box</label>
                <input type="number" id="pcs_in_box" name="pcs_in_box" class="form-control" value="{{ old('pcs_in_box', 0) }}" min="0">
                @error('pcs_in_box')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="supplier_id">Supplier ID</label>
                <input type="number" id="supplier_id" name="supplier_id" class="form-control" value="{{ old('supplier_id') }}">
                @error('supplier_id')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="bonus_type">Bonus Type</label>
                <select id="bonus_type" name="bonus_type" class="form-control">
                    <option value="D" {{ old('bonus_type', 'D') == 'D' ? 'selected' : '' }}>Deduct (D)</option>
                    <option value="A" {{ old('bonus_type') == 'A' ? 'selected' : '' }}>Add (A)</option>
                </select>
                @error('bonus_type')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="expire_date">Expire Date</label>
                <input type="date" id="expire_date" name="expire_date" class="form-control" value="{{ old('expire_date') }}">
                @error('expire_date')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="packing">Packing</label>
            <input type="text" id="packing" name="packing" class="form-control" value="{{ old('packing') }}">
            @error('packing')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <h3 style="margin: 30px 0 20px 0; color: #333;">Opening Quantities</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="opening_qty_box">Opening Qty (Box)</label>
                <input type="number" id="opening_qty_box" name="opening_qty_box" class="form-control" value="{{ old('opening_qty_box', 0) }}" min="0">
                @error('opening_qty_box')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="opening_qty_pcs">Opening Qty (Pcs)</label>
                <input type="number" id="opening_qty_pcs" name="opening_qty_pcs" class="form-control" value="{{ old('opening_qty_pcs', 0) }}" min="0">
                @error('opening_qty_pcs')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h3 style="margin: 30px 0 20px 0; color: #333;">Minimum Stock</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="minimum_stock_box">Minimum Stock (Box)</label>
                <input type="number" id="minimum_stock_box" name="minimum_stock_box" class="form-control" value="{{ old('minimum_stock_box', 0) }}" min="0">
                @error('minimum_stock_box')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="minimum_stock_pcs">Minimum Stock (Pcs)</label>
                <input type="number" id="minimum_stock_pcs" name="minimum_stock_pcs" class="form-control" value="{{ old('minimum_stock_pcs', 0) }}" min="0">
                @error('minimum_stock_pcs')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h3 style="margin: 30px 0 20px 0; color: #333;">Normal Price (N)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="n_price_box">Price per Box</label>
                <input type="number" id="n_price_box" name="n_price_box" class="form-control" value="{{ old('n_price_box', 0) }}" step="0.01" min="0">
                @error('n_price_box')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="n_price_pcs">Price per Pcs</label>
                <input type="number" id="n_price_pcs" name="n_price_pcs" class="form-control" value="{{ old('n_price_pcs', 0) }}" step="0.01" min="0">
                @error('n_price_pcs')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h3 style="margin: 30px 0 20px 0; color: #333;">Trade Price (T)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="t_price_box">Price per Box</label>
                <input type="number" id="t_price_box" name="t_price_box" class="form-control" value="{{ old('t_price_box', 0) }}" step="0.01" min="0">
                @error('t_price_box')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="t_price_pcs">Price per Pcs</label>
                <input type="number" id="t_price_pcs" name="t_price_pcs" class="form-control" value="{{ old('t_price_pcs', 0) }}" step="0.01" min="0">
                @error('t_price_pcs')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h3 style="margin: 30px 0 20px 0; color: #333;">Retail Price (R)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="r_price_box">Price per Box</label>
                <input type="number" id="r_price_box" name="r_price_box" class="form-control" value="{{ old('r_price_box', 0) }}" step="0.01" min="0">
                @error('r_price_box')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="r_price_pcs">Price per Pcs</label>
                <input type="number" id="r_price_pcs" name="r_price_pcs" class="form-control" value="{{ old('r_price_pcs', 0) }}" step="0.01" min="0">
                @error('r_price_pcs')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="sales_tax">Sales Tax (%)</label>
                <input type="number" id="sales_tax" name="sales_tax" class="form-control" value="{{ old('sales_tax', 0) }}" step="0.01" min="0" max="100">
                @error('sales_tax')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="rate_in_percent">Rate in Percent (%)</label>
                <input type="number" id="rate_in_percent" name="rate_in_percent" class="form-control" value="{{ old('rate_in_percent', 0) }}" step="0.01" min="0" max="100">
                @error('rate_in_percent')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="default_rate_type">Default Rate Type</label>
                <select id="default_rate_type" name="default_rate_type" class="form-control">
                    <option value="N" {{ old('default_rate_type', 'N') == 'N' ? 'selected' : '' }}>Normal (N)</option>
                    <option value="T" {{ old('default_rate_type') == 'T' ? 'selected' : '' }}>Trade (T)</option>
                    <option value="R" {{ old('default_rate_type') == 'R' ? 'selected' : '' }}>Retail (R)</option>
                </select>
                @error('default_rate_type')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="company_id">Company ID</label>
            <input type="number" id="company_id" name="company_id" class="form-control" value="{{ old('company_id') }}">
            @error('company_id')
                <div style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn">Create Product</button>
            <a href="{{ route_include_subdirectory('products.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Cancel</a>
        </div>
    </form>
</div>
@endsection

