<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Salesman;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $invoices = SalesInvoice::orderByDesc('id')->paginate(20);
        return view('tenant.sales_invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name', 'address', 'mobile']);
        $salesmen = Salesman::orderBy('name')->get(['id', 'name', 'address', 'mobile']);
        $products = Product::orderBy('product_name')->get(['product_code', 'product_name', 'packing', 'pcs_in_box', 'sales_tax', 'r_price_pcs']);
        $nextInvoiceNo = (int) (SalesInvoice::max('invoice_no') ?? 0) + 1;
        return view('tenant.sales_invoices.create', compact('customers', 'salesmen', 'products', 'nextInvoiceNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|integer',
            'invoice_date' => 'required|date',
            'salesman_code' => 'nullable|string|max:10',
            'salesman_name' => 'nullable|string|max:100',
            'customer_code' => 'nullable|string|max:20',
            'customer_name' => 'nullable|string|max:150',
            'address' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'previous_balance' => 'nullable|numeric',

            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string|max:50',
            'items.*.product_name' => 'nullable|string|max:150',
            'items.*.pack' => 'nullable|string|max:50',
            'items.*.box' => 'nullable|integer',
            'items.*.pcs' => 'nullable|integer',
            'items.*.rate' => 'nullable|numeric',
            'items.*.b_per_box' => 'nullable|numeric',
            'items.*.stx' => 'nullable|numeric',
            'items.*.discount' => 'nullable|numeric',
            'items.*.net_amount' => 'nullable|numeric',
        ]);

        DB::connection('tenant')->transaction(function () use ($validated) {
            $invoice = SalesInvoice::create([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'salesman_code' => $validated['salesman_code'] ?? null,
                'salesman_name' => $validated['salesman_name'] ?? null,
                'customer_code' => $validated['customer_code'] ?? null,
                'customer_name' => $validated['customer_name'] ?? null,
                'address' => $validated['address'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'previous_balance' => $validated['previous_balance'] ?? 0,
            ]);

            foreach ($validated['items'] as $item) {
                if (($item['box'] ?? 0) == 0 && ($item['pcs'] ?? 0) == 0) {
                    continue;
                }
                $invoice->items()->create([
                    'product_code' => $item['product_code'] ?? null,
                    'product_name' => $item['product_name'] ?? null,
                    'pack' => $item['pack'] ?? null,
                    'box' => (int) ($item['box'] ?? 0),
                    'pcs' => (int) ($item['pcs'] ?? 0),
                    'rate' => (float) ($item['rate'] ?? 0),
                    'b_per_box' => (float) ($item['b_per_box'] ?? 0),
                    'stx' => (float) ($item['stx'] ?? 0),
                    'discount' => (float) ($item['discount'] ?? 0),
                    'net_amount' => (float) ($item['net_amount'] ?? 0),
                ]);
            }
        });

        return redirect(route_include_subdirectory('sales_invoices.index'))
            ->with('success', 'Invoice created successfully');
    }

    public function show(SalesInvoice $sales_invoice)
    {
        $sales_invoice->load('items');
        return view('tenant.sales_invoices.show', ['invoice' => $sales_invoice]);
    }
}


