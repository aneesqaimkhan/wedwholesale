<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Area;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('area')->orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::orderBy('name')->get();
        return view('tenant.customers.create', compact('areas'));
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

        Customer::create($request->all());

        // dd($request->all() ,request()->route('subdomain'));

        return redirect(route_include_subdirectory('customers.index'))
        ->with('success', 'Customer created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load('area');
        return view('tenant.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $areas = Area::orderBy('name')->get();
        return view('tenant.customers.edit', compact('customer', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $customer->update($request->all());

            return redirect(route_include_subdirectory('customers.index'))
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect(route_include_subdirectory('customers.index'))
        ->with('success', 'Customer deleted successfully');

    }
}
