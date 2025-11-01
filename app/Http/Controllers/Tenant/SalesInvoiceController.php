<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Salesman;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $validator = Validator::make($request->all(), [
            'invoice_no' => ['required', 'integer', 'min:1', Rule::unique('sales_invoices', 'invoice_no')],
            'invoice_date' => 'required|date',
            'salesman_code' => 'nullable|string|max:10',
            'salesman_name' => 'nullable|string|max:100',
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'remarks' => 'required|string|max:255',
            'previous_balance' => 'nullable|numeric',

            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string|max:50',
            'items.*.product_name' => 'nullable|string|max:150',
            'items.*.pack' => 'nullable|string|max:50',
            'items.*.box' => 'nullable|integer|min:0',
            'items.*.pcs' => 'nullable|integer|min:0',
            'items.*.rate' => 'nullable|numeric|min:0',
            'items.*.b_per_box' => 'nullable|numeric|min:0',
            'items.*.stx' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.net_amount' => 'nullable|numeric|min:0',
        ]);

        $validator->after(function ($v) use ($request) {
            $items = $request->input('items', []);
            $validItemCount = 0;

            foreach ($items as $index => $item) {
                $box = (int) ($item['box'] ?? 0);
                $pcs = (int) ($item['pcs'] ?? 0);
                $net = (float) ($item['net_amount'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);
                $bpb = (float) ($item['b_per_box'] ?? 0);
                $stx = (float) ($item['stx'] ?? 0);
                $disc = (float) ($item['discount'] ?? 0);
                $pack = trim((string) ($item['pack'] ?? ''));

                $hasQty = ($box > 0 || $pcs > 0 || $net > 0);
                $anyFieldUsed = $hasQty || $rate > 0 || $bpb > 0 || $stx > 0 || $disc > 0 || $pack !== '';
                $hasProductCode = !empty($item['product_code']);
                $hasProductName = !empty($item['product_name']);
                $hasProduct = $hasProductCode && $hasProductName;

                if ($anyFieldUsed && !$hasProduct) {
                    if (!$hasProductCode) {
                        $v->errors()->add("items.$index.product_code", 'Product code is required for item #' . ($index + 1));
                    }
                    if (!$hasProductName) {
                        $v->errors()->add("items.$index.product_name", 'Product name is required for item #' . ($index + 1));
                    }
                }

                if ($hasQty && $hasProduct) {
                    $validItemCount++;
                }
            }

            if ($validItemCount === 0) {
                $v->errors()->add('items', 'At least one item with quantity or amount is required.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

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

    public function update(Request $request, SalesInvoice $sales_invoice)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no' => ['required', 'integer', 'min:1', Rule::unique('sales_invoices', 'invoice_no')->ignore($sales_invoice->id)],
            'invoice_date' => 'required|date',
            'salesman_code' => 'nullable|string|max:10',
            'salesman_name' => 'nullable|string|max:100',
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'remarks' => 'required|string|max:255',
            'previous_balance' => 'nullable|numeric',

            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string|max:50',
            'items.*.product_name' => 'nullable|string|max:150',
            'items.*.pack' => 'nullable|string|max:50',
            'items.*.box' => 'nullable|integer|min:0',
            'items.*.pcs' => 'nullable|integer|min:0',
            'items.*.rate' => 'nullable|numeric|min:0',
            'items.*.b_per_box' => 'nullable|numeric|min:0',
            'items.*.stx' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.net_amount' => 'nullable|numeric|min:0',
        ]);

        $validator->after(function ($v) use ($request) {
            $items = $request->input('items', []);
            $validItemCount = 0;

            foreach ($items as $index => $item) {
                $box = (int) ($item['box'] ?? 0);
                $pcs = (int) ($item['pcs'] ?? 0);
                $net = (float) ($item['net_amount'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);
                $bpb = (float) ($item['b_per_box'] ?? 0);
                $stx = (float) ($item['stx'] ?? 0);
                $disc = (float) ($item['discount'] ?? 0);
                $pack = trim((string) ($item['pack'] ?? ''));

                $hasQty = ($box > 0 || $pcs > 0 || $net > 0);
                $anyFieldUsed = $hasQty || $rate > 0 || $bpb > 0 || $stx > 0 || $disc > 0 || $pack !== '';
                $hasProductCode = !empty($item['product_code']);
                $hasProductName = !empty($item['product_name']);
                $hasProduct = $hasProductCode && $hasProductName;

                if ($anyFieldUsed && !$hasProduct) {
                    if (!$hasProductCode) {
                        $v->errors()->add("items.$index.product_code", 'Product code is required for item #' . ($index + 1));
                    }
                    if (!$hasProductName) {
                        $v->errors()->add("items.$index.product_name", 'Product name is required for item #' . ($index + 1));
                    }
                }

                if ($hasQty && $hasProduct) {
                    $validItemCount++;
                }
            }

            if ($validItemCount === 0) {
                $v->errors()->add('items', 'At least one item with quantity or amount is required.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        DB::connection('tenant')->transaction(function () use ($sales_invoice, $validated) {
            $sales_invoice->update([
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

            // Replace items (simple approach)
            $sales_invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                if (($item['box'] ?? 0) == 0 && ($item['pcs'] ?? 0) == 0 && ($item['net_amount'] ?? 0) == 0) {
                    continue;
                }
                $sales_invoice->items()->create([
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

        return redirect(route_include_subdirectory('sales_invoices.show', $sales_invoice))
            ->with('success', 'Invoice updated successfully');
    }
}


