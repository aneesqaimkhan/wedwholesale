<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Company;
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
        $companies = Company::orderBy('name')->get();
        return view('tenant.products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'packing' => 'nullable|string|max:100',
            'opening_qty_box' => 'nullable|integer|min:0',
            'minimum_stock_box' => 'nullable|integer|min:0',
            'n_price_box' => 'nullable|numeric|min:0',
            't_price_box' => 'nullable|numeric|min:0',
            'r_price_box' => 'nullable|numeric|min:0',
            'sales_tax' => 'nullable|numeric|min:0|max:100',
            'rate_in_percent' => 'nullable|numeric|min:0|max:100',
            'default_rate_type' => 'nullable|in:T,R,N',
            'company_id' => 'nullable|integer',
        ]);

        $data = $request->all();
        
        // Convert empty/null price values to 0 to match database defaults
        $data['n_price_box'] = ($data['n_price_box'] === null || $data['n_price_box'] === '') ? 0 : $data['n_price_box'];
        $data['t_price_box'] = ($data['t_price_box'] === null || $data['t_price_box'] === '') ? 0 : $data['t_price_box'];
        $data['r_price_box'] = ($data['r_price_box'] === null || $data['r_price_box'] === '') ? 0 : $data['r_price_box'];
        $data['sales_tax'] = ($data['sales_tax'] === null || $data['sales_tax'] === '') ? 0 : $data['sales_tax'];
        $data['rate_in_percent'] = ($data['rate_in_percent'] === null || $data['rate_in_percent'] === '') ? 0 : $data['rate_in_percent'];
        $data['opening_qty_box'] = ($data['opening_qty_box'] === null || $data['opening_qty_box'] === '') ? 0 : $data['opening_qty_box'];
        $data['minimum_stock_box'] = ($data['minimum_stock_box'] === null || $data['minimum_stock_box'] === '') ? 0 : $data['minimum_stock_box'];

        Product::create($data);

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
        $companies = Company::orderBy('name')->get();
        return view('tenant.products.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'packing' => 'nullable|string|max:100',
            'opening_qty_box' => 'nullable|integer|min:0',
            'minimum_stock_box' => 'nullable|integer|min:0',
            'n_price_box' => 'nullable|numeric|min:0',
            't_price_box' => 'nullable|numeric|min:0',
            'r_price_box' => 'nullable|numeric|min:0',
            'sales_tax' => 'nullable|numeric|min:0|max:100',
            'rate_in_percent' => 'nullable|numeric|min:0|max:100',
            'default_rate_type' => 'nullable|in:T,R,N',
            'company_id' => 'nullable|integer',
        ]);

        $data = $request->all();
        
        // Convert empty/null price values to 0 to match database defaults
        $data['n_price_box'] = ($data['n_price_box'] === null || $data['n_price_box'] === '') ? 0 : $data['n_price_box'];
        $data['t_price_box'] = ($data['t_price_box'] === null || $data['t_price_box'] === '') ? 0 : $data['t_price_box'];
        $data['r_price_box'] = ($data['r_price_box'] === null || $data['r_price_box'] === '') ? 0 : $data['r_price_box'];
        $data['sales_tax'] = ($data['sales_tax'] === null || $data['sales_tax'] === '') ? 0 : $data['sales_tax'];
        $data['rate_in_percent'] = ($data['rate_in_percent'] === null || $data['rate_in_percent'] === '') ? 0 : $data['rate_in_percent'];
        $data['opening_qty_box'] = ($data['opening_qty_box'] === null || $data['opening_qty_box'] === '') ? 0 : $data['opening_qty_box'];
        $data['minimum_stock_box'] = ($data['minimum_stock_box'] === null || $data['minimum_stock_box'] === '') ? 0 : $data['minimum_stock_box'];

        $product->update($data);

       
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

