@extends('tenant.layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<style>
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        margin-top: 12px;
    }
    .product-card {
        background: white;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        padding: 12px;
        transition: box-shadow 0.2s;
    }
    .product-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .product-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e1e5e9;
    }
    .product-code {
        font-size: 11px;
        color: #666;
        font-weight: 500;
    }
    .product-id {
        font-size: 10px;
        color: #999;
    }
    .product-name {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        font-size: 11px;
        margin-bottom: 8px;
    }
    .product-detail-item {
        display: flex;
        flex-direction: column;
    }
    .product-detail-label {
        color: #666;
        font-size: 10px;
        margin-bottom: 2px;
    }
    .product-detail-value {
        color: #333;
        font-weight: 500;
    }
    .product-actions {
        display: flex;
        gap: 6px;
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid #e1e5e9;
    }
    .product-actions .btn {
        flex: 1;
        padding: 4px 8px;
        font-size: 11px;
        text-align: center;
    }
    .empty-state {
        text-align: center;
        padding: 30px;
        color: #666;
        font-size: 12px;
    }
</style>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Product List</h3>
        <a href="{{ route_include_subdirectory('products.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Product</a>
    </div>

    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <div class="product-card-header">
                    <div>
                        <div class="product-code">{{ $product->product_code }}</div>
                        <div class="product-id">ID: {{ $product->product_id }}</div>
                    </div>
                </div>
                <div class="product-name">{{ $product->product_name }}</div>
                <div class="product-details">
                    <div class="product-detail-item">
                        <span class="product-detail-label">Pcs in Box</span>
                        <span class="product-detail-value">{{ $product->pcs_in_box }}</span>
                    </div>
                    <div class="product-detail-item">
                        <span class="product-detail-label">Price (Box)</span>
                        <span class="product-detail-value">{{ number_format($product->n_price_box, 2) }}</span>
                    </div>
                    <div class="product-detail-item">
                        <span class="product-detail-label">Price (Pcs)</span>
                        <span class="product-detail-value">{{ number_format($product->n_price_pcs, 2) }}</span>
                    </div>
                    <div class="product-detail-item">
                        <span class="product-detail-label">Stock</span>
                        <span class="product-detail-value">{{ $product->opening_qty_box }}/{{ $product->opening_qty_pcs }}</span>
                    </div>
                </div>
                <div class="product-actions">
                    <a href="{{ route_include_subdirectory('products.show', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" class="btn btn-success">View</a>
                    <a href="{{ route_include_subdirectory('products.edit', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" class="btn btn-warning">Edit</a>
                    <form method="POST" action="{{ route_include_subdirectory('products.destroy', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" style="display: inline; flex: 1;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 12px; font-size: 12px;">
            {{ $products->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>No products found. <a href="{{ route_include_subdirectory('products.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first product</a></p>
        </div>
    @endif
</div>
@endsection
