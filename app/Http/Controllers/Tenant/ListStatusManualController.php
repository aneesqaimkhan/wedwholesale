<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ListStatusManualController extends Controller
{
    /**
     * Display the main page with options
     */
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        return view('tenant.list_status_manual.index', compact('companies'));
    }

    /**
     * Display all products (All Companies option)
     */
    public function allProducts()
    {
        $products = Product::with('company')
            ->orderBy('product_code')
            ->get();

        return view('tenant.list_status_manual.products', [
            'products' => $products,
            'viewType' => 'all',
            'companyName' => 'All Companies'
        ]);
    }

    /**
     * Display products filtered by company (Companies Wise Product option)
     */
    public function companyProducts(Request $request)
    {
        $companyId = $request->get('company_id');
        
        if (!$companyId) {
            return redirect(route_include_subdirectory('list_status_manual.index'))
                ->with('error', 'Please select a company');
        }

        $company = Company::findOrFail($companyId);
        
        $products = Product::where('company_id', $companyId)
            ->with('company')
            ->orderBy('product_code')
            ->get();

        return view('tenant.list_status_manual.products', [
            'products' => $products,
            'viewType' => 'company',
            'companyName' => $company->name,
            'companyId' => $companyId
        ]);
    }
}

