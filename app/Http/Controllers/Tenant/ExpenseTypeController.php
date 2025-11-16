<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseTypes = ExpenseType::orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.expense_types.index', compact('expenseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.expense_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ExpenseType::create($request->all());

        return redirect(route_include_subdirectory('expense_types.index'))
            ->with('success', 'Expense type created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseType $expenseType)
    {
        return view('tenant.expense_types.show', compact('expenseType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseType $expenseType)
    {
        return view('tenant.expense_types.edit', compact('expenseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $expenseType->update($request->all());

        return redirect(route_include_subdirectory('expense_types.index'))
            ->with('success', 'Expense type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expenseType)
    {
        $expenseType->delete();

        return redirect(route_include_subdirectory('expense_types.index'))
            ->with('success', 'Expense type deleted successfully');
    }
}

