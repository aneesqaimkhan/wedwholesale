<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Area;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with('area')->orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::orderBy('name')->get();
        return view('tenant.suppliers.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        Supplier::create($request->all());

        return redirect(route_include_subdirectory('suppliers.index'))
        ->with('success', 'Supplier created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('area');
        return view('tenant.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $areas = Area::orderBy('name')->get();
        return view('tenant.suppliers.edit', compact('supplier', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $supplier->update($request->all());

            return redirect(route_include_subdirectory('suppliers.index'))
            ->with('success', 'Supplier updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect(route_include_subdirectory('suppliers.index'))
        ->with('success', 'Supplier deleted successfully');

    }
}

