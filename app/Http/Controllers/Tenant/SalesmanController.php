<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Salesman;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesmen = Salesman::orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.salesmen.index', compact('salesmen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.salesmen.create');
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
        ]);

        Salesman::create($request->all());

        return redirect()->route('salesmen.index', ['subdomain' => request()->route('subdomain')])
            ->with('success', 'Salesman created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salesman $salesman)
    {
        return view('tenant.salesmen.show', compact('salesman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salesman $salesman)
    {
        return view('tenant.salesmen.edit', compact('salesman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salesman $salesman)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $salesman->update($request->all());

        return redirect()->route('salesmen.index', ['subdomain' => request()->route('subdomain')])
            ->with('success', 'Salesman updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salesman $salesman)
    {
        $salesman->delete();

        return redirect()->route('salesmen.index', ['subdomain' => request()->route('subdomain')])
            ->with('success', 'Salesman deleted successfully.');
    }
}
