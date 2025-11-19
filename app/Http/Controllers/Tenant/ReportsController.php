<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Salesman;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Show report types selection page
     */
    public function index()
    {
        return view('tenant.reports.index');
    }

    /**
     * Stock Report
     */
    public function stock(Request $request)
    {
        $query = Product::query();

        // Filters
        $companyId = $request->input('company_id');
        $supplierId = $request->input('supplier_id');
        $lowStock = $request->input('low_stock', false);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($lowStock) {
            $query->whereRaw('(opening_qty_box + COALESCE((SELECT SUM(box) FROM purchase_items WHERE product_code = products.product_code), 0) - COALESCE((SELECT SUM(box) FROM sales_invoice_items WHERE product_code = products.product_code), 0)) <= minimum_stock_box');
        }

        $products = $query->orderBy('product_name')->get();

        // Calculate current stock for each product
        $products = $products->map(function ($product) {
            // Get total purchased boxes
            $purchasedBoxes = PurchaseItem::where('product_code', $product->product_code)
                ->sum('box');
            
            // Get total sold boxes
            $soldBoxes = SalesInvoiceItem::where('product_code', $product->product_code)
                ->sum('box');
            
            // Calculate current stock
            $currentStockBox = $product->opening_qty_box + $purchasedBoxes - $soldBoxes;
            
            $product->current_stock_box = $currentStockBox;
            $product->current_stock_pcs = $currentStockBox * $product->pcs_in_box;
            $product->is_low_stock = $currentStockBox <= $product->minimum_stock_box;
            
            return $product;
        });

        $companies = Company::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('tenant.reports.stock', compact('products', 'companies', 'suppliers', 'companyId', 'supplierId', 'lowStock'));
    }

    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $query = SalesInvoice::query();

        // Filters
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customerCode = $request->input('customer_code');
        $salesmanCode = $request->input('salesman_code');

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        if ($customerCode) {
            $query->where('customer_code', $customerCode);
        }

        if ($salesmanCode) {
            $query->where('salesman_code', $salesmanCode);
        }

        $invoices = $query->with('items')->orderByDesc('invoice_date')->orderByDesc('id')->get();

        // Calculate totals
        $totalSales = 0;
        $totalItems = 0;
        foreach ($invoices as $invoice) {
            $invoiceTotal = $invoice->items->sum('net_amount');
            $invoice->total_amount = $invoiceTotal;
            $totalSales += $invoiceTotal;
            $totalItems += $invoice->items->count();
        }

        $customers = Customer::orderBy('name')->get();
        $salesmen = Salesman::orderBy('name')->get();

        return view('tenant.reports.sales', compact('invoices', 'totalSales', 'totalItems', 'customers', 'salesmen', 'fromDate', 'toDate', 'customerCode', 'salesmanCode'));
    }

    /**
     * Profit Report
     */
    public function profit(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $productCode = $request->input('product_code');

        // Get sales data
        $salesQuery = SalesInvoice::query();
        if ($fromDate) {
            $salesQuery->where('invoice_date', '>=', $fromDate);
        }
        if ($toDate) {
            $salesQuery->where('invoice_date', '<=', $toDate);
        }
        $salesInvoices = $salesQuery->with('items')->get();

        // Get purchase data
        $purchaseQuery = Purchase::query();
        if ($fromDate) {
            $purchaseQuery->where('invoice_date', '>=', $fromDate);
        }
        if ($toDate) {
            $purchaseQuery->where('invoice_date', '<=', $toDate);
        }
        $purchases = $purchaseQuery->with('items')->get();

        // Calculate profit by product
        $profitData = [];
        
        // Get all products sold
        foreach ($salesInvoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($productCode && $item->product_code != $productCode) {
                    continue;
                }

                if (!isset($profitData[$item->product_code])) {
                    $profitData[$item->product_code] = [
                        'product_code' => $item->product_code,
                        'product_name' => $item->product_name,
                        'total_sold_boxes' => 0,
                        'total_sold_pcs' => 0,
                        'total_sales_amount' => 0,
                        'total_cost_amount' => 0,
                        'profit' => 0,
                    ];
                }

                $profitData[$item->product_code]['total_sold_boxes'] += $item->box;
                $profitData[$item->product_code]['total_sold_pcs'] += $item->pcs;
                $profitData[$item->product_code]['total_sales_amount'] += $item->net_amount;
            }
        }

        // Calculate cost from purchases - get average cost per box for each product
        $purchaseCosts = [];
        foreach ($purchases as $purchase) {
            foreach ($purchase->items as $item) {
                if ($productCode && $item->product_code != $productCode) {
                    continue;
                }

                if (!isset($purchaseCosts[$item->product_code])) {
                    $purchaseCosts[$item->product_code] = [
                        'total_cost' => 0,
                        'total_boxes' => 0,
                    ];
                }

                $purchaseCosts[$item->product_code]['total_cost'] += $item->net_amount;
                $purchaseCosts[$item->product_code]['total_boxes'] += $item->box;
            }
        }

        // Calculate cost for sold products
        foreach ($profitData as $code => &$data) {
            if (isset($purchaseCosts[$code]) && $purchaseCosts[$code]['total_boxes'] > 0) {
                $avgCostPerBox = $purchaseCosts[$code]['total_cost'] / $purchaseCosts[$code]['total_boxes'];
                $data['total_cost_amount'] = $avgCostPerBox * $data['total_sold_boxes'];
            }
        }

        // Calculate profit
        foreach ($profitData as $code => &$data) {
            $data['profit'] = $data['total_sales_amount'] - $data['total_cost_amount'];
            $data['profit_percentage'] = $data['total_sales_amount'] > 0 
                ? ($data['profit'] / $data['total_sales_amount']) * 100 
                : 0;
        }

        $totalSales = array_sum(array_column($profitData, 'total_sales_amount'));
        $totalCost = array_sum(array_column($profitData, 'total_cost_amount'));
        $totalProfit = $totalSales - $totalCost;

        $products = Product::orderBy('product_name')->get();

        return view('tenant.reports.profit', compact('profitData', 'totalSales', 'totalCost', 'totalProfit', 'products', 'fromDate', 'toDate', 'productCode'));
    }

    /**
     * Customer Report
     */
    public function customer(Request $request)
    {
        $customerCode = $request->input('customer_code');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = SalesInvoice::query();

        if ($customerCode) {
            $query->where('customer_code', $customerCode);
        }

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        $invoices = $query->with('items')->orderByDesc('invoice_date')->get();

        // Group by customer
        $customerData = [];
        foreach ($invoices as $invoice) {
            if (!isset($customerData[$invoice->customer_code])) {
                $customer = Customer::find($invoice->customer_code);
                $customerData[$invoice->customer_code] = [
                    'customer_code' => $invoice->customer_code,
                    'customer_name' => $customer ? $customer->name : $invoice->customer_name,
                    'total_invoices' => 0,
                    'total_amount' => 0,
                    'invoices' => [],
                ];
            }

            $invoiceTotal = $invoice->items->sum('net_amount');
            $customerData[$invoice->customer_code]['total_invoices']++;
            $customerData[$invoice->customer_code]['total_amount'] += $invoiceTotal;
            $customerData[$invoice->customer_code]['invoices'][] = $invoice;
        }

        $customers = Customer::orderBy('name')->get();

        return view('tenant.reports.customer', compact('customerData', 'customers', 'customerCode', 'fromDate', 'toDate'));
    }

    /**
     * Supplier Report
     */
    public function supplier(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = Purchase::query();

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        $purchases = $query->with('items')->orderByDesc('invoice_date')->get();

        // Group by supplier (via company)
        $supplierData = [];
        foreach ($purchases as $purchase) {
            $company = Company::where('code', $purchase->company_code)->first();
            $supplierIdKey = $company ? $company->id : $purchase->company_code;

            if (!isset($supplierData[$supplierIdKey])) {
                $supplierData[$supplierIdKey] = [
                    'supplier_id' => $supplierIdKey,
                    'supplier_name' => $company ? $company->name : $purchase->company_name,
                    'total_purchases' => 0,
                    'total_amount' => 0,
                    'purchases' => [],
                ];
            }

            $purchaseTotal = $purchase->items->sum('net_amount');
            $supplierData[$supplierIdKey]['total_purchases']++;
            $supplierData[$supplierIdKey]['total_amount'] += $purchaseTotal;
            $supplierData[$supplierIdKey]['purchases'][] = $purchase;
        }

        // Filter by supplier if specified
        if ($supplierId) {
            $supplierData = array_filter($supplierData, function($data) use ($supplierId) {
                return $data['supplier_id'] == $supplierId;
            });
        }

        $suppliers = Supplier::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        return view('tenant.reports.supplier', compact('supplierData', 'suppliers', 'companies', 'supplierId', 'fromDate', 'toDate'));
    }

    /**
     * Expense Report
     */
    public function expense(Request $request)
    {
        $query = Expense::with('expenseType');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $expenseTypeId = $request->input('expense_type_id');

        if ($fromDate) {
            $query->where('date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('date', '<=', $toDate);
        }

        if ($expenseTypeId) {
            $query->where('expense_type_id', $expenseTypeId);
        }

        $expenses = $query->orderByDesc('date')->get();

        $totalAmount = $expenses->sum('amount');

        // Group by expense type
        $expenseByType = [];
        foreach ($expenses as $expense) {
            $typeName = $expense->expenseType ? $expense->expenseType->name : 'Unknown';
            if (!isset($expenseByType[$typeName])) {
                $expenseByType[$typeName] = 0;
            }
            $expenseByType[$typeName] += $expense->amount;
        }

        $expenseTypes = ExpenseType::orderBy('name')->get();

        return view('tenant.reports.expense', compact('expenses', 'totalAmount', 'expenseByType', 'expenseTypes', 'fromDate', 'toDate', 'expenseTypeId'));
    }
}

