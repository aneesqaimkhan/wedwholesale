<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('tenant.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        Company::create($request->all());

        return redirect(route_include_subdirectory('companies.index'))
            ->with('success', 'Company created successfully');
    }

    public function show(Company $company)
    {
        return view('tenant.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('tenant.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $company->update($request->all());

        return redirect(route_include_subdirectory('companies.index'))
            ->with('success', 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect(route_include_subdirectory('companies.index'))
            ->with('success', 'Company deleted successfully');
    }
}
