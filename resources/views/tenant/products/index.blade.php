@extends('tenant.layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="page-header">
    <h1 class="page-title">Products</h1>
    <p class="page-subtitle">Manage your product database</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Product List</h3>
        <a href="{{ route_include_subdirectory('products.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn">Add New Product</a>
    </div>

    @if($products->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Product Name</th>
                    <th>Pcs in Box</th>
                    <th>Price (Box)</th>
                    <th>Price (Pcs)</th>
                    <th>Stock (Box/Pcs)</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_id }}</td>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->pcs_in_box }}</td>
                    <td>{{ number_format($product->n_price_box, 2) }}</td>
                    <td>{{ number_format($product->n_price_pcs, 2) }}</td>
                    <td>{{ $product->opening_qty_box }}/{{ $product->opening_qty_pcs }}</td>
                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route_include_subdirectory('products.show', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">View</a>
                        <a href="{{ route_include_subdirectory('products.edit', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                        <form method="POST" action="{{ route_include_subdirectory('products.destroy', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>No products found. <a href="{{ route_include_subdirectory('products.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first product</a></p>
        </div>
    @endif
</div>
@endsection

