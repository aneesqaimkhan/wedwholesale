<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::orderByDesc('id')->paginate(20);
        return view('tenant.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get(['id', 'name', 'address', 'mobile']);
        $products = Product::orderBy('product_name')->get(['product_id', 'product_code', 'product_name', 'packing', 'pcs_in_box', 'sales_tax', 'n_price_box', 't_price_box', 'r_price_box', 'default_rate_type']);
        $nextInvoiceNo = (int) (Purchase::max('invoice_no') ?? 0) + 1;
        return view('tenant.purchases.create', compact('suppliers', 'products', 'nextInvoiceNo'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no' => ['required', 'integer', 'min:1', Rule::unique('purchases', 'invoice_no')],
            'invoice_date' => 'required|date',
            'supplier_code' => 'required|string|max:20',
            'supplier_name' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'remarks' => 'required|string|max:255',
            'previous_balance' => 'nullable|numeric',

            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string|max:50',
            'items.*.product_name' => 'nullable|string|max:150',
            'items.*.pack' => 'nullable|string|max:50',
            'items.*.box' => 'nullable|integer|min:0',
            'items.*.rate' => 'nullable|numeric|min:0',
            'items.*.b_per_box' => 'nullable|numeric|min:0',
            'items.*.stx' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.net_amount' => 'nullable|numeric|min:0',
            'items.*.selected_rate_type' => 'nullable|string|in:N,T,R',
        ]);

        $validator->after(function ($v) use ($request) {
            $items = $request->input('items', []);
            $validItemCount = 0;

            foreach ($items as $index => $item) {
                $box = (int) ($item['box'] ?? 0);
                $net = (float) ($item['net_amount'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);
                $bpb = (float) ($item['b_per_box'] ?? 0);
                $stx = (float) ($item['stx'] ?? 0);
                $disc = (float) ($item['discount'] ?? 0);
                $pack = trim((string) ($item['pack'] ?? ''));

                $hasQty = ($box > 0 || $net > 0);
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

        DB::transaction(function () use ($validated) {
            $purchase = Purchase::create([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'company_code' => $validated['supplier_code'] ?? null,
                'company_name' => $validated['supplier_name'] ?? null,
                'address' => $validated['address'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'previous_balance' => $validated['previous_balance'] ?? 0,
            ]);

            foreach ($validated['items'] as $item) {
                if (($item['box'] ?? 0) == 0) {
                    continue;
                }
                $purchase->items()->create([
                    'product_code' => $item['product_code'] ?? null,
                    'product_name' => $item['product_name'] ?? null,
                    'pack' => $item['pack'] ?? null,
                    'box' => (int) ($item['box'] ?? 0),
                    'pcs' => 0,
                    'rate' => (float) ($item['rate'] ?? 0),
                    'rate_type' => $item['selected_rate_type'] ?? 'N',
                    'b_per_box' => (float) ($item['b_per_box'] ?? 0),
                    'stx' => (float) ($item['stx'] ?? 0),
                    'discount' => (float) ($item['discount'] ?? 0),
                    'net_amount' => (float) ($item['net_amount'] ?? 0),
                ]);
            }
        });

        return redirect(route_include_subdirectory('purchases.index'))
            ->with('success', 'Purchase created successfully');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('items');
        return view('tenant.purchases.show', ['purchase' => $purchase]);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no' => ['required', 'integer', 'min:1', Rule::unique('purchases', 'invoice_no')->ignore($purchase->id)],
            'invoice_date' => 'required|date',
            'supplier_code' => 'required|string|max:20',
            'supplier_name' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'remarks' => 'required|string|max:255',
            'previous_balance' => 'nullable|numeric',

            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string|max:50',
            'items.*.product_name' => 'nullable|string|max:150',
            'items.*.pack' => 'nullable|string|max:50',
            'items.*.box' => 'nullable|integer|min:0',
            'items.*.rate' => 'nullable|numeric|min:0',
            'items.*.b_per_box' => 'nullable|numeric|min:0',
            'items.*.stx' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.net_amount' => 'nullable|numeric|min:0',
            'items.*.selected_rate_type' => 'nullable|string|in:N,T,R',
        ]);

        $validator->after(function ($v) use ($request) {
            $items = $request->input('items', []);
            $validItemCount = 0;

            foreach ($items as $index => $item) {
                $box = (int) ($item['box'] ?? 0);
                $net = (float) ($item['net_amount'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);
                $bpb = (float) ($item['b_per_box'] ?? 0);
                $stx = (float) ($item['stx'] ?? 0);
                $disc = (float) ($item['discount'] ?? 0);
                $pack = trim((string) ($item['pack'] ?? ''));

                $hasQty = ($box > 0 || $net > 0);
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

        DB::transaction(function () use ($purchase, $validated) {
            $purchase->update([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'company_code' => $validated['supplier_code'] ?? null,
                'company_name' => $validated['supplier_name'] ?? null,
                'address' => $validated['address'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'previous_balance' => $validated['previous_balance'] ?? 0,
            ]);

            $purchase->items()->delete();

            foreach ($validated['items'] as $item) {
                if (($item['box'] ?? 0) == 0 && ($item['net_amount'] ?? 0) == 0) {
                    continue;
                }
                $purchase->items()->create([
                    'product_code' => $item['product_code'] ?? null,
                    'product_name' => $item['product_name'] ?? null,
                    'pack' => $item['pack'] ?? null,
                    'box' => (int) ($item['box'] ?? 0),
                    'pcs' => 0,
                    'rate' => (float) ($item['rate'] ?? 0),
                    'rate_type' => $item['selected_rate_type'] ?? 'N',
                    'b_per_box' => (float) ($item['b_per_box'] ?? 0),
                    'stx' => (float) ($item['stx'] ?? 0),
                    'discount' => (float) ($item['discount'] ?? 0),
                    'net_amount' => (float) ($item['net_amount'] ?? 0),
                ]);
            }
        });

        return redirect(route_include_subdirectory('purchases.show', $purchase))
            ->with('success', 'Purchase updated successfully');
    }
}
