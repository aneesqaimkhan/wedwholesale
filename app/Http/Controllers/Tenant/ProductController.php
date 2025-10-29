<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'pcs_in_box' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|integer',
            'bonus_type' => 'nullable|in:A,D',
            'expire_date' => 'nullable|date',
            'packing' => 'nullable|string|max:100',
            'opening_qty_box' => 'nullable|integer|min:0',
            'opening_qty_pcs' => 'nullable|integer|min:0',
            'minimum_stock_box' => 'nullable|integer|min:0',
            'minimum_stock_pcs' => 'nullable|integer|min:0',
            'n_price_box' => 'nullable|numeric|min:0',
            'n_price_pcs' => 'nullable|numeric|min:0',
            't_price_box' => 'nullable|numeric|min:0',
            't_price_pcs' => 'nullable|numeric|min:0',
            'r_price_box' => 'nullable|numeric|min:0',
            'r_price_pcs' => 'nullable|numeric|min:0',
            'sales_tax' => 'nullable|numeric|min:0|max:100',
            'rate_in_percent' => 'nullable|numeric|min:0|max:100',
            'default_rate_type' => 'nullable|in:T,R,N',
            'company_id' => 'nullable|integer',
        ]);

        Product::create($request->all());

            return redirect(route_include_subdirectory('products.index'))
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('tenant.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('tenant.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'pcs_in_box' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|integer',
            'bonus_type' => 'nullable|in:A,D',
            'expire_date' => 'nullable|date',
            'packing' => 'nullable|string|max:100',
            'opening_qty_box' => 'nullable|integer|min:0',
            'opening_qty_pcs' => 'nullable|integer|min:0',
            'minimum_stock_box' => 'nullable|integer|min:0',
            'minimum_stock_pcs' => 'nullable|integer|min:0',
            'n_price_box' => 'nullable|numeric|min:0',
            'n_price_pcs' => 'nullable|numeric|min:0',
            't_price_box' => 'nullable|numeric|min:0',
            't_price_pcs' => 'nullable|numeric|min:0',
            'r_price_box' => 'nullable|numeric|min:0',
            'r_price_pcs' => 'nullable|numeric|min:0',
            'sales_tax' => 'nullable|numeric|min:0|max:100',
            'rate_in_percent' => 'nullable|numeric|min:0|max:100',
            'default_rate_type' => 'nullable|in:T,R,N',
            'company_id' => 'nullable|integer',
        ]);

        $product->update($request->all());

       
            return redirect(route_include_subdirectory('products.index'))
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect(route_include_subdirectory('products.index'))
        ->with('success', 'Product deleted successfully');

    }
}

