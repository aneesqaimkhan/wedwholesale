<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::with('expenseType')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('tenant.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expenseTypes = ExpenseType::orderBy('name')->get();
        return view('tenant.expenses.create', compact('expenseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        Expense::create($request->all());

        return redirect(route_include_subdirectory('expenses.index'))
            ->with('success', 'Expense created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load('expenseType');
        return view('tenant.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $expenseTypes = ExpenseType::orderBy('name')->get();
        return view('tenant.expenses.edit', compact('expense', 'expenseTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect(route_include_subdirectory('expenses.index'))
            ->with('success', 'Expense updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect(route_include_subdirectory('expenses.index'))
            ->with('success', 'Expense deleted successfully');
    }
}

