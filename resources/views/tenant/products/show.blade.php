@extends('tenant.layouts.admin')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Product Details</h1>
    <p class="page-subtitle">View product information</p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Product Information</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route_include_subdirectory('products.edit', ['subdomain' => request()->route('subdomain'), 'product' => $product->product_id]) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route_include_subdirectory('products.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <h4 style="margin-bottom: 15px; color: #6D2D9D; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Basic Information</h4>
            
            <div style="margin-bottom: 15px;">
                <strong>Product ID:</strong>
                <div style="color: #666;">{{ $product->product_id }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Product Code:</strong>
                <div style="color: #666;">{{ $product->product_code }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Product Name:</strong>
                <div style="color: #666;">{{ $product->product_name }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Pcs in Box:</strong>
                <div style="color: #666;">{{ $product->pcs_in_box }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Supplier ID:</strong>
                <div style="color: #666;">{{ $product->supplier_id ?: 'Not set' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Bonus Type:</strong>
                <div style="color: #666;">{{ $product->bonus_type == 'A' ? 'Add (A)' : 'Deduct (D)' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Expire Date:</strong>
                <div style="color: #666;">{{ $product->expire_date ? $product->expire_date->format('M d, Y') : 'Not set' }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Packing:</strong>
                <div style="color: #666;">{{ $product->packing ?: 'Not set' }}</div>
            </div>
        </div>
        
        <div>
            <h4 style="margin-bottom: 15px; color: #6D2D9D; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Stock Information</h4>
            
            <div style="margin-bottom: 15px;">
                <strong>Opening Qty (Box):</strong>
                <div style="color: #666;">{{ $product->opening_qty_box }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Opening Qty (Pcs):</strong>
                <div style="color: #666;">{{ $product->opening_qty_pcs }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Minimum Stock (Box):</strong>
                <div style="color: #666;">{{ $product->minimum_stock_box }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Minimum Stock (Pcs):</strong>
                <div style="color: #666;">{{ $product->minimum_stock_pcs }}</div>
            </div>
            
            <h4 style="margin: 30px 0 15px 0; color: #6D2D9D; border-bottom: 2px solid #6D2D9D; padding-bottom: 10px;">Pricing Information</h4>
            
            <div style="margin-bottom: 15px;">
                <strong>Normal Price - Box:</strong>
                <div style="color: #666;">{{ number_format($product->n_price_box, 2) }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Normal Price - Pcs:</strong>
                <div style="color: #666;">{{ number_format($product->n_price_pcs, 2) }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Trade Price - Box:</strong>
                <div style="color: #666;">{{ number_format($product->t_price_box, 2) }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Trade Price - Pcs:</strong>
                <div style="color: #666;">{{ number_format($product->t_price_pcs, 2) }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Retail Price - Box:</strong>
                <div style="color: #666;">{{ number_format($product->r_price_box, 2) }}</div>
            </div>
            
            <div style="margin-bottom: 15px;">
                <strong>Retail Price - Pcs:</strong>
                <div style="color: #666;">{{ number_format($product->r_price_pcs, 2) }}</div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">
            <div>
                <strong>Sales Tax:</strong>
                <div style="color: #666;">{{ number_format($product->sales_tax, 2) }}%</div>
            </div>
            
            <div>
                <strong>Rate in Percent:</strong>
                <div style="color: #666;">{{ number_format($product->rate_in_percent, 2) }}%</div>
            </div>
            
            <div>
                <strong>Default Rate Type:</strong>
                <div style="color: #666;">
                    @if($product->default_rate_type == 'N')
                        Normal (N)
                    @elseif($product->default_rate_type == 'T')
                        Trade (T)
                    @else
                        Retail (R)
                    @endif
                </div>
            </div>
            
            <div>
                <strong>Company ID:</strong>
                <div style="color: #666;">{{ $product->company_id ?: 'Not set' }}</div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <strong>Created At:</strong>
                    <div style="color: #666;">{{ $product->created_at->format('M d, Y H:i') }}</div>
                </div>
                
                <div>
                    <strong>Last Updated:</strong>
                    <div style="color: #666;">{{ $product->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

