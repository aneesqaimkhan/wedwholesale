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
     * Sales Summary Report
     */
    public function salesSummary(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $groupBy = $request->input('group_by', 'none'); // none, daily, weekly, monthly, yearly

        $query = SalesInvoice::query();

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        $invoices = $query->with('items')->get();

        // Calculate totals
        $totalInvoices = $invoices->count();
        $totalSales = 0;
        $totalQuantityBox = 0;
        $totalQuantityPcs = 0;

        foreach ($invoices as $invoice) {
            $invoiceTotal = $invoice->items->sum('net_amount');
            $totalSales += $invoiceTotal;
            
            foreach ($invoice->items as $item) {
                $totalQuantityBox += $item->box;
                $totalQuantityPcs += $item->pcs;
            }
        }

        $averageInvoiceValue = $totalInvoices > 0 ? $totalSales / $totalInvoices : 0;

        // Group data if requested
        $groupedData = [];
        if ($groupBy !== 'none') {
            foreach ($invoices as $invoice) {
                $date = \Carbon\Carbon::parse($invoice->invoice_date);
                $key = '';

                switch ($groupBy) {
                    case 'daily':
                        $key = $date->format('Y-m-d');
                        break;
                    case 'weekly':
                        $key = $date->format('Y-W');
                        break;
                    case 'monthly':
                        $key = $date->format('Y-m');
                        break;
                    case 'yearly':
                        $key = $date->format('Y');
                        break;
                }

                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        'period' => $key,
                        'total_invoices' => 0,
                        'total_sales' => 0,
                        'total_quantity_box' => 0,
                        'total_quantity_pcs' => 0,
                    ];
                }

                $invoiceTotal = $invoice->items->sum('net_amount');
                $groupedData[$key]['total_invoices']++;
                $groupedData[$key]['total_sales'] += $invoiceTotal;

                foreach ($invoice->items as $item) {
                    $groupedData[$key]['total_quantity_box'] += $item->box;
                    $groupedData[$key]['total_quantity_pcs'] += $item->pcs;
                }
            }
        }

        return view('tenant.reports.sales-summary', compact(
            'totalInvoices',
            'totalSales',
            'totalQuantityBox',
            'totalQuantityPcs',
            'averageInvoiceValue',
            'groupedData',
            'groupBy',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Sales Invoice Detail Report
     */
    public function salesInvoiceDetail(Request $request)
    {
        $query = SalesInvoice::query();

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customerCode = $request->input('customer_code');
        $salesmanCode = $request->input('salesman_code');
        $invoiceNo = $request->input('invoice_no');

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

        if ($invoiceNo) {
            $query->where('invoice_no', 'like', '%' . $invoiceNo . '%');
        }

        $invoices = $query->with('items')->orderByDesc('invoice_date')->orderByDesc('id')->get();

        // Calculate invoice totals and current balances
        foreach ($invoices as $invoice) {
            $invoiceTotal = $invoice->items->sum('net_amount');
            $invoice->total_amount = $invoiceTotal;
            $invoice->current_balance = $invoice->previous_balance + $invoiceTotal;
        }

        $customers = Customer::orderBy('name')->get();
        $salesmen = Salesman::orderBy('name')->get();

        return view('tenant.reports.sales-invoice-detail', compact(
            'invoices',
            'customers',
            'salesmen',
            'fromDate',
            'toDate',
            'customerCode',
            'salesmanCode',
            'invoiceNo'
        ));
    }

    /**
     * Sales by Customer Report
     */
    public function salesByCustomer(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customerCode = $request->input('customer_code');
        $sortBy = $request->input('sort_by', 'total_sales'); // total_sales, customer_name

        $query = SalesInvoice::query();

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        if ($customerCode) {
            $query->where('customer_code', $customerCode);
        }

        $invoices = $query->with('items')->get();

        // Group by customer
        $customerData = [];
        foreach ($invoices as $invoice) {
            if (!isset($customerData[$invoice->customer_code])) {
                $customer = Customer::find($invoice->customer_code);
                $customerData[$invoice->customer_code] = [
                    'customer_code' => $invoice->customer_code,
                    'customer_name' => $customer ? $customer->name : $invoice->customer_name,
                    'number_of_invoices' => 0,
                    'total_sales_amount' => 0,
                    'total_quantity_box' => 0,
                    'total_quantity_pcs' => 0,
                    'outstanding_balance' => 0,
                ];
            }

            $invoiceTotal = $invoice->items->sum('net_amount');
            $customerData[$invoice->customer_code]['number_of_invoices']++;
            $customerData[$invoice->customer_code]['total_sales_amount'] += $invoiceTotal;
            $customerData[$invoice->customer_code]['outstanding_balance'] += $invoice->previous_balance + $invoiceTotal;

            foreach ($invoice->items as $item) {
                $customerData[$invoice->customer_code]['total_quantity_box'] += $item->box;
                $customerData[$invoice->customer_code]['total_quantity_pcs'] += $item->pcs;
            }
        }

        // Calculate average order value
        foreach ($customerData as &$data) {
            $data['average_order_value'] = $data['number_of_invoices'] > 0 
                ? $data['total_sales_amount'] / $data['number_of_invoices'] 
                : 0;
        }

        // Sort data
        if ($sortBy === 'total_sales') {
            uasort($customerData, function($a, $b) {
                return $b['total_sales_amount'] <=> $a['total_sales_amount'];
            });
        } else {
            uasort($customerData, function($a, $b) {
                return strcmp($a['customer_name'], $b['customer_name']);
            });
        }

        $customers = Customer::orderBy('name')->get();

        return view('tenant.reports.sales-by-customer', compact(
            'customerData',
            'customers',
            'fromDate',
            'toDate',
            'customerCode',
            'sortBy'
        ));
    }

    /**
     * Sales by Product Report
     */
    public function salesByProduct(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $productCode = $request->input('product_code');

        $query = SalesInvoice::query();

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        $invoices = $query->with('items')->get();

        // Group by product
        $productData = [];
        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($productCode && $item->product_code != $productCode) {
                    continue;
                }

                if (!isset($productData[$item->product_code])) {
                    $productData[$item->product_code] = [
                        'product_code' => $item->product_code,
                        'product_name' => $item->product_name,
                        'total_quantity_box' => 0,
                        'total_quantity_pcs' => 0,
                        'total_sales_amount' => 0,
                        'number_of_invoices' => 0,
                        'invoice_ids' => [],
                    ];
                }

                $productData[$item->product_code]['total_quantity_box'] += $item->box;
                $productData[$item->product_code]['total_quantity_pcs'] += $item->pcs;
                $productData[$item->product_code]['total_sales_amount'] += $item->net_amount;

                if (!in_array($invoice->id, $productData[$item->product_code]['invoice_ids'])) {
                    $productData[$item->product_code]['invoice_ids'][] = $invoice->id;
                    $productData[$item->product_code]['number_of_invoices']++;
                }
            }
        }

        // Calculate average selling price
        foreach ($productData as &$data) {
            $totalQuantity = $data['total_quantity_box'] + ($data['total_quantity_pcs'] / 100); // Convert pcs to boxes for calculation
            $data['average_selling_price'] = $totalQuantity > 0 
                ? $data['total_sales_amount'] / $totalQuantity 
                : 0;
        }

        // Sort by total sales amount descending
        uasort($productData, function($a, $b) {
            return $b['total_sales_amount'] <=> $a['total_sales_amount'];
        });

        $products = Product::orderBy('product_name')->get();

        return view('tenant.reports.sales-by-product', compact(
            'productData',
            'products',
            'fromDate',
            'toDate',
            'productCode'
        ));
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

    /**
     * Purchase Summary Report
     */
    public function purchaseSummary(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = Purchase::query();

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        $purchases = $query->with('items')->get();

        // Calculate totals
        $totalInvoices = $purchases->count();
        $totalPurchaseAmount = 0;
        $totalQuantityBox = 0;
        $totalQuantityPcs = 0;

        foreach ($purchases as $purchase) {
            $purchaseTotal = $purchase->items->sum('net_amount');
            $totalPurchaseAmount += $purchaseTotal;
            
            foreach ($purchase->items as $item) {
                $totalQuantityBox += $item->box;
                $totalQuantityPcs += $item->pcs;
            }
        }

        $averagePurchaseValue = $totalInvoices > 0 ? $totalPurchaseAmount / $totalInvoices : 0;

        return view('tenant.reports.purchase-summary', compact(
            'totalInvoices',
            'totalPurchaseAmount',
            'totalQuantityBox',
            'totalQuantityPcs',
            'averagePurchaseValue',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Purchase Invoice Detail Report
     */
    public function purchaseInvoiceDetail(Request $request)
    {
        $query = Purchase::query();

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $companyCode = $request->input('company_code');
        $invoiceNo = $request->input('invoice_no');

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        if ($companyCode) {
            $query->where('company_code', $companyCode);
        }

        if ($invoiceNo) {
            $query->where('invoice_no', 'like', '%' . $invoiceNo . '%');
        }

        $purchases = $query->with('items')->orderByDesc('invoice_date')->orderByDesc('id')->get();

        // Calculate purchase totals and current balances
        foreach ($purchases as $purchase) {
            $purchaseTotal = $purchase->items->sum('net_amount');
            $purchase->total_amount = $purchaseTotal;
            $purchase->current_balance = $purchase->previous_balance + $purchaseTotal;
        }

        $companies = Company::orderBy('name')->get();

        return view('tenant.reports.purchase-invoice-detail', compact(
            'purchases',
            'companies',
            'fromDate',
            'toDate',
            'companyCode',
            'invoiceNo'
        ));
    }

    /**
     * Purchase by Supplier Report
     */
    public function purchaseBySupplier(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $companyCode = $request->input('company_code');

        $query = Purchase::query();

        if ($fromDate) {
            $query->where('invoice_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('invoice_date', '<=', $toDate);
        }

        if ($companyCode) {
            $query->where('company_code', $companyCode);
        }

        $purchases = $query->with('items')->get();

        // Group by supplier (company)
        $supplierData = [];
        foreach ($purchases as $purchase) {
            $supplierKey = $purchase->company_code;

            if (!isset($supplierData[$supplierKey])) {
                $supplierData[$supplierKey] = [
                    'supplier_code' => $purchase->company_code,
                    'supplier_name' => $purchase->company_name,
                    'number_of_invoices' => 0,
                    'total_purchase_amount' => 0,
                    'total_quantity_box' => 0,
                    'total_quantity_pcs' => 0,
                    'outstanding_balance' => 0,
                ];
            }

            $purchaseTotal = $purchase->items->sum('net_amount');
            $supplierData[$supplierKey]['number_of_invoices']++;
            $supplierData[$supplierKey]['total_purchase_amount'] += $purchaseTotal;
            $supplierData[$supplierKey]['outstanding_balance'] += $purchase->previous_balance + $purchaseTotal;

            foreach ($purchase->items as $item) {
                $supplierData[$supplierKey]['total_quantity_box'] += $item->box;
                $supplierData[$supplierKey]['total_quantity_pcs'] += $item->pcs;
            }
        }

        // Sort by total purchases descending
        uasort($supplierData, function($a, $b) {
            return $b['total_purchase_amount'] <=> $a['total_purchase_amount'];
        });

        $companies = Company::orderBy('name')->get();

        return view('tenant.reports.purchase-by-supplier', compact(
            'supplierData',
            'companies',
            'fromDate',
            'toDate',
            'companyCode'
        ));
    }

    /**
     * Low Stock Report
     */
    public function lowStock(Request $request)
    {
        $query = Product::query();

        $supplierId = $request->input('supplier_id');
        $companyId = $request->input('company_id');

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $products = $query->orderBy('product_name')->get();

        // Calculate current stock and filter low stock items
        $lowStockProducts = [];
        foreach ($products as $product) {
            // Get total purchased boxes
            $purchasedBoxes = PurchaseItem::where('product_code', $product->product_code)
                ->sum('box');
            
            // Get total sold boxes
            $soldBoxes = SalesInvoiceItem::where('product_code', $product->product_code)
                ->sum('box');
            
            // Calculate current stock
            $currentStockBox = $product->opening_qty_box + $purchasedBoxes - $soldBoxes;
            $currentStockPcs = $currentStockBox * $product->pcs_in_box;
            
            // Check if low stock
            if ($currentStockBox <= $product->minimum_stock_box) {
                $stockDeficit = $product->minimum_stock_box - $currentStockBox;
                
                $supplier = $product->supplier_id ? Supplier::find($product->supplier_id) : null;
                
                $lowStockProducts[] = [
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'current_stock_box' => $currentStockBox,
                    'current_stock_pcs' => $currentStockPcs,
                    'minimum_stock_box' => $product->minimum_stock_box,
                    'minimum_stock_pcs' => $product->minimum_stock_pcs,
                    'stock_deficit' => $stockDeficit,
                    'supplier' => $supplier ? $supplier->name : 'N/A',
                    'company' => $product->company ? $product->company->name : 'N/A',
                ];
            }
        }

        $suppliers = Supplier::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        return view('tenant.reports.low-stock', compact(
            'lowStockProducts',
            'suppliers',
            'companies',
            'supplierId',
            'companyId'
        ));
    }

    /**
     * Stock Movement Report
     */
    public function stockMovement(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $productCode = $request->input('product_code');

        $query = Product::query();

        if ($productCode) {
            $query->where('product_code', $productCode);
        }

        $products = $query->orderBy('product_name')->get();

        $movementData = [];
        foreach ($products as $product) {
            // Opening stock
            $openingStockBox = $product->opening_qty_box;
            $openingStockPcs = $product->opening_qty_pcs;

            // Get purchases in date range
            $purchaseQuery = PurchaseItem::where('product_code', $product->product_code);
            if ($fromDate || $toDate) {
                $purchaseQuery->whereHas('purchase', function($q) use ($fromDate, $toDate) {
                    if ($fromDate) {
                        $q->where('invoice_date', '>=', $fromDate);
                    }
                    if ($toDate) {
                        $q->where('invoice_date', '<=', $toDate);
                    }
                });
            }
            $purchases = $purchaseQuery->get();
            $purchaseQuantityBox = $purchases->sum('box');
            $purchaseQuantityPcs = $purchases->sum('pcs');
            $purchaseAmount = $purchases->sum('net_amount');

            // Get sales in date range
            $salesQuery = SalesInvoiceItem::where('product_code', $product->product_code);
            if ($fromDate || $toDate) {
                $salesQuery->whereHas('invoice', function($q) use ($fromDate, $toDate) {
                    if ($fromDate) {
                        $q->where('invoice_date', '>=', $fromDate);
                    }
                    if ($toDate) {
                        $q->where('invoice_date', '<=', $toDate);
                    }
                });
            }
            $sales = $salesQuery->get();
            $salesQuantityBox = $sales->sum('box');
            $salesQuantityPcs = $sales->sum('pcs');
            $salesAmount = $sales->sum('net_amount');

            // Calculate closing stock
            $closingStockBox = $openingStockBox + $purchaseQuantityBox - $salesQuantityBox;
            $closingStockPcs = $openingStockPcs + $purchaseQuantityPcs - $salesQuantityPcs;

            $movementData[] = [
                'product_code' => $product->product_code,
                'product_name' => $product->product_name,
                'opening_stock_box' => $openingStockBox,
                'opening_stock_pcs' => $openingStockPcs,
                'purchase_quantity_box' => $purchaseQuantityBox,
                'purchase_quantity_pcs' => $purchaseQuantityPcs,
                'purchase_amount' => $purchaseAmount,
                'sales_quantity_box' => $salesQuantityBox,
                'sales_quantity_pcs' => $salesQuantityPcs,
                'sales_amount' => $salesAmount,
                'closing_stock_box' => $closingStockBox,
                'closing_stock_pcs' => $closingStockPcs,
            ];
        }

        $productsList = Product::orderBy('product_name')->get();

        return view('tenant.reports.stock-movement', compact(
            'movementData',
            'productsList',
            'fromDate',
            'toDate',
            'productCode'
        ));
    }
}

