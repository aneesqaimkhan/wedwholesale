<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Company;
use App\Models\ReceiptPayment;
use App\Models\Salesman;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReceiptPaymentController extends Controller
{
    public function index()
    {
        $payments = ReceiptPayment::orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function($payment) {
                return $payment->payment_date->format('Y-m-d');
            });
        
        return view('tenant.receipt_payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name', 'mobile']);
        $salesmen = Salesman::orderBy('name')->get(['id', 'name', 'mobile']);
        $suppliers = Supplier::orderBy('name')->get(['id', 'name', 'mobile']);
        return view('tenant.receipt_payments.create', compact('customers', 'salesmen', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.id' => 'nullable|integer|exists:receipt_payments,id',
            'entries.*.customer_code' => 'nullable|string|max:20',
            'entries.*.customer_name' => 'nullable|string|max:150',
            'entries.*.supplier_code' => 'nullable|string|max:20',
            'entries.*.supplier_name' => 'nullable|string|max:150',
            'entries.*.receipt' => 'nullable|numeric|min:0',
            'entries.*.payment' => 'nullable|numeric|min:0',
            'entries.*.remarks' => 'nullable|string',
        ]);

        $validator->after(function ($v) use ($request) {
            $entries = $request->input('entries', []);
            $validEntryCount = 0;

            foreach ($entries as $index => $entry) {
                $customerCode = $entry['customer_code'] ?? '';
                $supplierCode = $entry['supplier_code'] ?? '';
                $receipt = (float) ($entry['receipt'] ?? 0);
                $payment = (float) ($entry['payment'] ?? 0);

                if (empty($customerCode) && empty($supplierCode)) {
                    continue; // Skip empty rows
                }

                // Validate that only customer OR supplier is selected, not both
                if (!empty($customerCode) && !empty($supplierCode)) {
                    $v->errors()->add("entries.$index.customer_code", 'Please select either Customer OR Supplier, not both for entry #' . ($index + 1));
                    continue;
                }

                // Validate based on selection
                if (!empty($customerCode)) {
                    // Customer selected: receipt required, payment should be 0
                    if ($receipt <= 0) {
                        $v->errors()->add("entries.$index.receipt", 'Receipt must be greater than 0 for customer entry #' . ($index + 1));
                    } else {
                        $validEntryCount++;
                    }
                } elseif (!empty($supplierCode)) {
                    // Supplier selected: payment required, receipt should be 0
                    if ($payment <= 0) {
                        $v->errors()->add("entries.$index.payment", 'Payment must be greater than 0 for supplier entry #' . ($index + 1));
                    } else {
                        $validEntryCount++;
                    }
                }
            }

            if ($validEntryCount === 0) {
                $v->errors()->add('entries', 'At least one valid entry with customer or supplier and receipt/payment is required.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $paymentDate = $validated['payment_date'];
        $entries = $validated['entries'];

        // Get all existing record IDs for this date
        $existingIds = ReceiptPayment::whereDate('payment_date', $paymentDate)
            ->pluck('id')
            ->toArray();
        
        $submittedIds = [];
        $updatedCount = 0;
        $createdCount = 0;

        // Process entries: update existing or create new
        foreach ($entries as $entry) {
            if (empty($entry['customer_code']) && empty($entry['supplier_code'])) {
                // Skip empty rows - if they have an ID, they will be deleted below
                continue;
            }

            // Set customer if provided (mutually exclusive with supplier)
            if (!empty($entry['customer_code'])) {
                $paymentData = [
                    'invoice_no' => null,
                    'payment_date' => $paymentDate,
                    'payment_from' => 'customer',
                    'entity_code' => $entry['customer_code'],
                    'entity_name' => $entry['customer_name'] ?? '',
                    'supplier_code' => null,
                    'supplier_name' => null,
                    'receipt' => $entry['receipt'] ?? 0,
                    'payment' => 0, // Payment is 0 for customers
                    'remarks' => $entry['remarks'] ?? null,
                ];
            } elseif (!empty($entry['supplier_code'])) {
                // Set supplier if provided (mutually exclusive with customer)
                $paymentData = [
                    'invoice_no' => null,
                    'payment_date' => $paymentDate,
                    'payment_from' => 'salesman',
                    'entity_code' => null,
                    'entity_name' => null,
                    'supplier_code' => $entry['supplier_code'],
                    'supplier_name' => $entry['supplier_name'] ?? '',
                    'receipt' => 0, // Receipt is 0 for suppliers
                    'payment' => $entry['payment'] ?? 0,
                    'remarks' => $entry['remarks'] ?? null,
                ];
            } else {
                continue; // Skip if neither is selected
            }

            // Update existing record or create new one
            if (!empty($entry['id'])) {
                $record = ReceiptPayment::find($entry['id']);
                if ($record) {
                    $record->update($paymentData);
                    $updatedCount++;
                    $submittedIds[] = $entry['id'];
                }
            } else {
                ReceiptPayment::create($paymentData);
                $createdCount++;
            }
        }

        // Delete records that were removed from the form
        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            ReceiptPayment::whereIn('id', $idsToDelete)->delete();
        }

        $message = 'Receipt/Payment saved successfully';
        if ($createdCount > 0 && $updatedCount > 0) {
            $message .= " ({$createdCount} created, {$updatedCount} updated)";
        } elseif ($createdCount > 0) {
            $message .= " ({$createdCount} created)";
        } elseif ($updatedCount > 0) {
            $message .= " ({$updatedCount} updated)";
        }

        return redirect(route_include_subdirectory('receipt_payments.index'))
            ->with('success', $message);
    }

    public function show(ReceiptPayment $receipt_payment)
    {
        return view('tenant.receipt_payments.show', compact('receipt_payment'));
    }

    public function edit(ReceiptPayment $receipt_payment)
    {
        $customers = Customer::orderBy('name')->get(['id', 'name', 'mobile']);
        $salesmen = Salesman::orderBy('name')->get(['id', 'name', 'mobile']);
        return view('tenant.receipt_payments.edit', compact('receipt_payment', 'customers', 'salesmen'));
    }

    public function update(Request $request, ReceiptPayment $receipt_payment)
    {
        $request->validate([
            'payment_from' => 'required|in:customer,salesman',
            'entity_code' => 'required|string|max:20',
            'entity_name' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $receipt_payment->update($request->all());

        return redirect(route_include_subdirectory('receipt_payments.index'))
            ->with('success', 'Receipt/Payment updated successfully');
    }

    public function destroy(ReceiptPayment $receipt_payment)
    {
        $receipt_payment->delete();

        return redirect(route_include_subdirectory('receipt_payments.index'))
            ->with('success', 'Receipt/Payment deleted successfully');
    }

    public function getPreviousBalance(Request $request)
    {
        $type = $request->input('type'); // 'customer' or 'supplier'
        $code = $request->input('code'); // customer_code or supplier_code

        if (!$type || !$code) {
            return response()->json([
                'success' => false,
                'message' => 'Type and code are required',
                'balance' => 0
            ], 400);
        }

        $balance = 0;

        if ($type === 'customer') {
            // Calculate customer balance: Sum of sales invoice items net_amount - Sum of receipts
            $salesTotal = DB::table('sales_invoice_items as items')
                ->join('sales_invoices as invoices', 'items.invoice_id', '=', 'invoices.id')
                ->where('invoices.customer_code', $code)
                ->sum('items.net_amount') ?? 0;

            $receiptsTotal = ReceiptPayment::where('payment_from', 'customer')
                ->where('entity_code', $code)
                ->sum('receipt') ?? 0;

            $balance = $salesTotal - $receiptsTotal;
        } elseif ($type === 'supplier') {
            // Calculate supplier balance: Sum of purchase items net_amount - Sum of payments
            // Note: purchases table uses company_code, but we're querying by supplier_code
            // We need to check if purchases table has supplier_code or if we need to join differently
            $purchasesTotal = DB::table('purchase_items as items')
                ->join('purchases', 'items.purchase_id', '=', 'purchases.id')
                ->where('purchases.company_code', $code)
                ->sum('items.net_amount') ?? 0;

            $paymentsTotal = ReceiptPayment::where('supplier_code', $code)
                ->sum('payment') ?? 0;

            $balance = $purchasesTotal - $paymentsTotal;
        }

        return response()->json([
            'success' => true,
            'balance' => round($balance, 2)
        ]);
    }

    public function getByDate(Request $request)
    {
        $date = $request->input('date');
        
        if (!$date) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required',
                'records' => []
            ], 400);
        }

        $records = ReceiptPayment::whereDate('payment_date', $date)
            ->orderBy('id')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'customer_code' => $record->entity_code ?? '',
                    'customer_name' => $record->payment_from === 'customer' ? ($record->entity_name ?? '') : '',
                    'supplier_code' => $record->supplier_code ?? '',
                    'supplier_name' => $record->supplier_name ?? '',
                    'receipt' => (float) $record->receipt,
                    'payment' => (float) $record->payment,
                    'remarks' => $record->remarks ?? '',
                ];
            });

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }
}
