@extends('tenant.layouts.admin')

@section('title', 'List Status Manual')
@section('page-title', 'List Status Manual')

@section('content')
<style>
    .options-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .option-card {
        background: white;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .option-card:hover {
        border-color: #6D2D9D;
        box-shadow: 0 4px 12px rgba(109, 45, 157, 0.2);
        transform: translateY(-2px);
    }
    .option-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    .option-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    .option-description {
        font-size: 13px;
        color: #666;
        margin-bottom: 15px;
    }
    .company-select-form {
        margin-top: 15px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 13px;
    }
    .form-control {
        width: 100%;
        padding: 10px;
        border: 2px solid #e1e5e9;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    .form-control:focus {
        outline: none;
        border-color: #6D2D9D;
    }
    .btn-submit {
        width: 100%;
        padding: 10px;
        background: #6D2D9D;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-submit:hover {
        background: #5a2470;
    }
</style>

<div class="card">
    <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 600; color: #333;">Select Option</h3>
    <p style="margin: 0 0 20px 0; font-size: 13px; color: #666;">Choose how you want to view the product list</p>

    <div class="options-container">
        <!-- Option 1: All Companies -->
        <a href="{{ route_include_subdirectory('list_status_manual.all_products', ['subdomain' => request()->route('subdomain')]) }}" class="option-card">
            <div class="option-icon">🏢</div>
            <div class="option-title">All Companies</div>
            <div class="option-description">View all products from all companies</div>
        </a>

        <!-- Option 2: Companies Wise Product -->
        <div class="option-card" style="cursor: default;">
            <div class="option-icon">📋</div>
            <div class="option-title">Companies Wise Product</div>
            <div class="option-description">View products filtered by company</div>
            
            <form action="{{ route_include_subdirectory('list_status_manual.company_products', ['subdomain' => request()->route('subdomain')]) }}" method="GET" class="company-select-form">
                <div class="form-group">
                    <label for="company_id">Select Company:</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-submit">View Products</button>
            </form>
        </div>
    </div>
</div>
@endsection

