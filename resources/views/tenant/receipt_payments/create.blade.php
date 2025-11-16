@extends('tenant.layouts.admin')

@section('title', 'Add Receipt/Payment')
@section('page-title', 'Add Receipt/Payment')

@section('content')
<style>
    .compact-form {
        font-size: 13px;
    }
    .compact-form .form-group {
        margin-bottom: 8px;
    }
    .compact-form .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: #666;
        margin-bottom: 2px;
    }
    .compact-form .form-control {
        padding: 5px 8px;
        font-size: 12px;
        height: 30px;
    }
    .compact-form textarea.form-control {
        height: auto;
        min-height: 55px;
    }
    .compact-form .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .compact-form .error {
        font-size: 11px;
        margin-top: 3px;
    }
    .entries-grid {
        margin-top: 15px;
    }
    .entries-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .entries-table th,
    .entries-table td {
        padding: 6px 4px;
        border: 1px solid #dee2e6;
        text-align: left;
    }
    .entries-table th {
        background-color: #6D2D9D;
        color: white;
        font-weight: 600;
        font-size: 11px;
    }
    .entries-table td input {
        width: 100%;
        padding: 4px;
        font-size: 12px;
        border: 1px solid #ced4da;
        border-radius: 3px;
    }
    .entries-table td input[type="number"] {
        text-align: right;
    }
    .totals-section {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .totals-section h4 {
        font-size: 14px;
        color: #6D2D9D;
        margin-bottom: 12px;
    }
    .totals-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .totals-table th,
    .totals-table td {
        padding: 8px;
        border: 1px solid #dee2e6;
    }
    .totals-table th {
        background-color: #6D2D9D;
        color: white;
        font-weight: 600;
    }
    .totals-table td {
        text-align: right;
    }
    .totals-table .total-row {
        font-weight: 600;
        background-color: #e9ecef;
    }
</style>

<div class="card compact-form">
    <form method="POST" action="{{ route_include_subdirectory('receipt_payments.store', ['subdomain' => request()->route('subdomain')]) }}" id="receipt_payment_form">
        @csrf
        
        <!-- Payment Date at the top -->
        <div style="margin-bottom: 15px;">
            <div class="form-group">
                <label for="payment_date">Payment Date *</label>
                <input type="date" id="payment_date" name="payment_date" class="form-control" value="{{ old('payment_date', request()->get('date', date('Y-m-d'))) }}" required>
                @error('payment_date')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Entries Grid -->
        <div class="entries-grid">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="font-size: 12px;">Entries</strong>
                <button type="button" class="btn" id="add_row" style="padding: 6px 16px; font-size: 12px; background: #6D2D9D; color: white;">+ Add Row</button>
            </div>

            <div class="table-responsive">
                <table class="entries-table" id="entries_table">
                    <thead>
                        <tr>
                            <th style="width: 18%;">Customer</th>
                            <th style="width: 18%;">Supplier</th>
                            <th style="width: 20%;">Remarks</th>
                            <th style="width: 12%;">Prev. Balance</th>
                            <th style="width: 12%;">Receipt</th>
                            <th style="width: 12%;">Payment</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Totals Section -->
        <div class="totals-section" id="totals_section" style="display: none;">
            <h4>Totals by Customer/Supplier</h4>
            <table class="totals-table" id="totals_table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Customer/Supplier</th>
                        <th style="width: 20%;">Total Receipt</th>
                        <th style="width: 20%;">Total Payment</th>
                        <th style="width: 20%;">Net Amount</th>
                    </tr>
                </thead>
                <tbody id="totals_body"></tbody>
                <tfoot>
                    <tr class="total-row">
                        <td><strong>Grand Total</strong></td>
                        <td id="grand_receipt_total">0.00</td>
                        <td id="grand_payment_total">0.00</td>
                        <td id="grand_net_total">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="display: flex; gap: 8px; margin-top: 15px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px; background: #6D2D9D; color: white;">Save Receipt/Payment</button>
            <a href="{{ route_include_subdirectory('receipt_payments.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px; color: white;">Cancel</a>
        </div>
        
        <div id="records_info" style="margin-top: 10px; font-size: 11px; color: #666; font-style: italic; display: none;">
            <span id="records_count">0</span> record(s) found for this date. You can edit them below or add new entries.
        </div>
    </form>
</div>

<!-- Datalists for autocomplete -->
<datalist id="customer_list">
    @foreach($customers as $c)
        <option value="{{ $c->name }}" data-id="{{ $c->id }}" data-name="{{ $c->name }}"></option>
    @endforeach
</datalist>

<datalist id="supplier_list">
    @foreach($suppliers as $supplier)
        <option value="{{ $supplier->name }}" data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}"></option>
    @endforeach
</datalist>

<script>
(function() {
    let rowIndex = 0;
    const addRowBtn = document.getElementById('add_row');
    const tableBody = document.querySelector('#entries_table tbody');
    const customerList = document.getElementById('customer_list');
    const supplierList = document.getElementById('supplier_list');
    
    // Function to fetch previous balance
    function fetchPreviousBalance(type, code, balanceInput) {
        if (!code) {
            balanceInput.value = '0.00';
            return;
        }
        
        const url = '{{ route_include_subdirectory("receipt_payments.get_previous_balance", ["subdomain" => request()->route("subdomain")]) }}';
        fetch(url + '?type=' + encodeURIComponent(type) + '&code=' + encodeURIComponent(code))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    balanceInput.value = parseFloat(data.balance || 0).toFixed(2);
                } else {
                    balanceInput.value = '0.00';
                }
            })
            .catch(error => {
                console.error('Error fetching previous balance:', error);
                balanceInput.value = '0.00';
            });
    }

    function calculateTotals() {
        const rows = document.querySelectorAll('#entries_table tbody tr');
        const customerTotals = {};
        const supplierTotals = {};
        let grandReceiptTotal = 0;
        let grandPaymentTotal = 0;

        rows.forEach(row => {
            const customerInput = row.querySelector('.customer_input');
            const supplierInput = row.querySelector('.supplier_input');
            const receiptInput = row.querySelector('.receipt_input');
            const paymentInput = row.querySelector('.payment_input');
            
            const customerName = customerInput ? customerInput.value.trim() : '';
            const supplierName = supplierInput ? supplierInput.value.trim() : '';
            const receipt = parseFloat(receiptInput ? receiptInput.value : 0) || 0;
            const payment = parseFloat(paymentInput ? paymentInput.value : 0) || 0;

            if (receipt > 0 || payment > 0) {
                // Add to grand totals
                grandReceiptTotal += receipt;
                grandPaymentTotal += payment;
                
                // Add to customer totals
                if (customerName) {
                    if (!customerTotals[customerName]) {
                        customerTotals[customerName] = { receipt: 0, payment: 0 };
                    }
                    customerTotals[customerName].receipt += receipt;
                    customerTotals[customerName].payment += payment;
                }
                
                // Add to supplier totals
                if (supplierName) {
                    if (!supplierTotals[supplierName]) {
                        supplierTotals[supplierName] = { receipt: 0, payment: 0 };
                    }
                    supplierTotals[supplierName].receipt += receipt;
                    supplierTotals[supplierName].payment += payment;
                }
            }
        });

        // Update totals table
        const totalsBody = document.getElementById('totals_body');
        const totalsSection = document.getElementById('totals_section');
        const grandReceiptEl = document.getElementById('grand_receipt_total');
        const grandPaymentEl = document.getElementById('grand_payment_total');
        const grandNetEl = document.getElementById('grand_net_total');

        totalsBody.innerHTML = '';
        
        const hasTotals = Object.keys(customerTotals).length > 0 || Object.keys(supplierTotals).length > 0;
        
        if (hasTotals) {
            totalsSection.style.display = 'block';
            
            // Add customer totals
            Object.keys(customerTotals).sort().forEach(name => {
                const total = customerTotals[name];
                const net = total.receipt - total.payment;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>Customer:</strong> ${name}</td>
                    <td>${total.receipt.toFixed(2)}</td>
                    <td>${total.payment.toFixed(2)}</td>
                    <td>${net.toFixed(2)}</td>
                `;
                totalsBody.appendChild(row);
            });
            
            // Add supplier totals
            Object.keys(supplierTotals).sort().forEach(name => {
                const total = supplierTotals[name];
                const net = total.receipt - total.payment;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>Supplier:</strong> ${name}</td>
                    <td>${total.receipt.toFixed(2)}</td>
                    <td>${total.payment.toFixed(2)}</td>
                    <td>${net.toFixed(2)}</td>
                `;
                totalsBody.appendChild(row);
            });
            
            const grandNet = grandReceiptTotal - grandPaymentTotal;
            grandReceiptEl.textContent = grandReceiptTotal.toFixed(2);
            grandPaymentEl.textContent = grandPaymentTotal.toFixed(2);
            grandNetEl.textContent = grandNet.toFixed(2);
        } else {
            totalsSection.style.display = 'none';
            grandReceiptEl.textContent = '0.00';
            grandPaymentEl.textContent = '0.00';
            grandNetEl.textContent = '0.00';
        }
    }

    function createRow(recordData = null) {
        const row = document.createElement('tr');
        const recordId = recordData ? recordData.id : '';
        const customerNameValue = recordData ? (recordData.customer_name || '') : '';
        const supplierNameValue = recordData ? (recordData.supplier_name || '') : '';
        const receiptValue = recordData ? (recordData.receipt || 0) : 0;
        const paymentValue = recordData ? (recordData.payment || 0) : 0;
        const remarksValue = recordData ? (recordData.remarks || '') : '';
        const customerCodeValue = recordData ? (recordData.customer_code || '') : '';
        const supplierCodeValue = recordData ? (recordData.supplier_code || '') : '';
        
        row.innerHTML = `
            <td>
                <input list="customer_list" class="form-control customer_input" placeholder="Search customer" autocomplete="off" value="${customerNameValue}">
                <input type="hidden" name="entries[${rowIndex}][id]" class="entry_id" value="${recordId}">
                <input type="hidden" name="entries[${rowIndex}][customer_code]" class="customer_code" value="${customerCodeValue}">
                <input type="hidden" name="entries[${rowIndex}][customer_name]" class="customer_name" value="${customerNameValue}">
            </td>
            <td>
                <input list="supplier_list" class="form-control supplier_input" placeholder="Search supplier" autocomplete="off" value="${supplierNameValue}">
                <input type="hidden" name="entries[${rowIndex}][supplier_code]" class="supplier_code" value="${supplierCodeValue}">
                <input type="hidden" name="entries[${rowIndex}][supplier_name]" class="supplier_name" value="${supplierNameValue}">
            </td>
            <td>
                <input type="text" name="entries[${rowIndex}][remarks]" class="form-control remarks_input" placeholder="Remarks" value="${remarksValue}">
            </td>
            <td>
                <input type="text" class="form-control previous_balance" placeholder="0.00" value="0.00" readonly style="background-color: #e9ecef; text-align: right;">
            </td>
            <td>
                <input type="number" step="0.01" name="entries[${rowIndex}][receipt]" class="form-control receipt_input" placeholder="0.00" value="${receiptValue}" min="0">
            </td>
            <td>
                <input type="number" step="0.01" name="entries[${rowIndex}][payment]" class="form-control payment_input" placeholder="0.00" value="${paymentValue}" min="0">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove_row" style="padding: 4px 8px; font-size: 11px; background: #dc3545; color: white; border: none;">X</button>
            </td>
        `;

        // Wire up customer input - make it mutually exclusive with supplier
        const customerInput = row.querySelector('.customer_input');
        const customerCode = row.querySelector('.customer_code');
        const customerName = row.querySelector('.customer_name');
        const supplierInput = row.querySelector('.supplier_input');
        const supplierCode = row.querySelector('.supplier_code');
        const supplierName = row.querySelector('.supplier_name');
        const receiptInput = row.querySelector('.receipt_input');
        const paymentInput = row.querySelector('.payment_input');
        const previousBalanceInput = row.querySelector('.previous_balance');

        // If record data exists, set up initial state
        if (recordData) {
            if (customerCode.value) {
                // Customer record - enable receipt, disable payment
                receiptInput.disabled = false;
                receiptInput.style.backgroundColor = '';
                paymentInput.disabled = true;
                paymentInput.style.backgroundColor = '#e9ecef';
                fetchPreviousBalance('customer', customerCode.value, previousBalanceInput);
            } else if (supplierCode.value) {
                // Supplier record - enable payment, disable receipt
                paymentInput.disabled = false;
                paymentInput.style.backgroundColor = '';
                receiptInput.disabled = true;
                receiptInput.style.backgroundColor = '#e9ecef';
                fetchPreviousBalance('supplier', supplierCode.value, previousBalanceInput);
            }
        }

        customerInput.addEventListener('change', function() {
            const val = this.value;
            
            if (val) {
                // Clear supplier when customer is selected
                supplierInput.value = '';
                supplierCode.value = '';
                supplierName.value = '';
                
                // Enable receipt, disable payment for customer
                receiptInput.disabled = false;
                receiptInput.style.backgroundColor = '';
                paymentInput.disabled = true;
                paymentInput.style.backgroundColor = '#e9ecef';
                paymentInput.value = '0';
                
                // Find and set customer
                for (let i = 0; i < customerList.options.length; i++) {
                    if (customerList.options[i].value === val) {
                        const customerId = customerList.options[i].getAttribute('data-id') || '';
                        customerCode.value = customerId;
                        customerName.value = customerList.options[i].getAttribute('data-name') || '';
                        
                        // Fetch previous balance for customer
                        fetchPreviousBalance('customer', customerId, previousBalanceInput);
                        calculateTotals();
                        break;
                    }
                }
            } else {
                customerCode.value = '';
                customerName.value = '';
                previousBalanceInput.value = '0.00';
                // Enable both if nothing selected
                receiptInput.disabled = false;
                receiptInput.style.backgroundColor = '';
                paymentInput.disabled = false;
                paymentInput.style.backgroundColor = '';
                calculateTotals();
            }
        });

        // Wire up supplier input - make it mutually exclusive with customer
        supplierInput.addEventListener('change', function() {
            const val = this.value;
            
            if (val) {
                // Clear customer when supplier is selected
                customerInput.value = '';
                customerCode.value = '';
                customerName.value = '';
                
                // Enable payment, disable receipt for supplier
                paymentInput.disabled = false;
                paymentInput.style.backgroundColor = '';
                receiptInput.disabled = true;
                receiptInput.style.backgroundColor = '#e9ecef';
                receiptInput.value = '0';
                
                // Find and set supplier
                for (let i = 0; i < supplierList.options.length; i++) {
                    if (supplierList.options[i].value === val) {
                        const supplierId = supplierList.options[i].getAttribute('data-id') || '';
                        supplierCode.value = supplierId;
                        supplierName.value = supplierList.options[i].getAttribute('data-name') || '';
                        
                        // Fetch previous balance for supplier
                        fetchPreviousBalance('supplier', supplierId, previousBalanceInput);
                        calculateTotals();
                        break;
                    }
                }
            } else {
                supplierCode.value = '';
                supplierName.value = '';
                previousBalanceInput.value = '0.00';
                // Enable both if nothing selected
                receiptInput.disabled = false;
                receiptInput.style.backgroundColor = '';
                paymentInput.disabled = false;
                paymentInput.style.backgroundColor = '';
                calculateTotals();
            }
        });

        // Initially both are enabled (will be disabled based on selection or existing data)
        if (!recordData) {
            receiptInput.disabled = false;
            paymentInput.disabled = false;
        }
        
        receiptInput.addEventListener('input', calculateTotals);
        receiptInput.addEventListener('change', calculateTotals);
        paymentInput.addEventListener('input', calculateTotals);
        paymentInput.addEventListener('change', calculateTotals);

        // Wire up remove button
        row.querySelector('.remove_row').addEventListener('click', function() {
            row.remove();
            calculateTotals();
            
            // Ensure at least one row exists
            if (tableBody.children.length === 0) {
                addRow();
            }
        });

        tableBody.appendChild(row);
        rowIndex++;
    }

    function addRow() {
        createRow();
    }

    // Ensure button exists before adding event listener
    if (addRowBtn) {
        addRowBtn.addEventListener('click', function(e) {
            e.preventDefault();
            addRow();
        });
    }
    
    // Function to load records by date
    function loadRecordsByDate(date) {
        const recordsInfo = document.getElementById('records_info');
        const recordsCount = document.getElementById('records_count');
        
        if (!date) {
            // Clear table and add one empty row
            tableBody.innerHTML = '';
            rowIndex = 0;
            addRow();
            recordsInfo.style.display = 'none';
            return;
        }
        
        // Use the same route helper as get-previous-balance
        const url = '{{ route_include_subdirectory("receipt_payments.get_by_date", ["subdomain" => request()->route("subdomain")]) }}';
        const fullUrl = url + '?date=' + encodeURIComponent(date);
        
        console.log('Fetching URL:', fullUrl);
        
        fetch(fullUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response HTML:', text.substring(0, 500));
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Response is not JSON. Content-Type:', contentType);
                        console.error('Response text:', text.substring(0, 500));
                        throw new Error('Response is not JSON');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                tableBody.innerHTML = '';
                rowIndex = 0;
                
                if (data.success && data.records && data.records.length > 0) {
                    // Load existing records
                    data.records.forEach(record => {
                        createRow(record);
                    });
                    recordsCount.textContent = data.records.length;
                    recordsInfo.style.display = 'block';
                } else {
                    // No records found, add one empty row
                    addRow();
                    recordsInfo.style.display = 'none';
                }
                calculateTotals();
            })
            .catch(error => {
                console.error('Error loading records:', error);
                console.error('Failed URL:', fullUrl);
                tableBody.innerHTML = '';
                rowIndex = 0;
                addRow();
                recordsInfo.style.display = 'none';
            });
    }
    
    // Listen for date changes
    const paymentDateInput = document.getElementById('payment_date');
    paymentDateInput.addEventListener('change', function() {
        loadRecordsByDate(this.value);
    });
    
    // Load records on page load if date is set
    if (paymentDateInput.value) {
        loadRecordsByDate(paymentDateInput.value);
    } else {
        // Add initial row
        addRow();
    }

    // Form validation
    document.getElementById('receipt_payment_form').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('#entries_table tbody tr');
        let hasValidEntry = false;

        rows.forEach(row => {
            const customerCode = row.querySelector('.customer_code').value;
            const supplierCode = row.querySelector('.supplier_code').value;
            const receipt = parseFloat(row.querySelector('.receipt_input').value || 0);
            const payment = parseFloat(row.querySelector('.payment_input').value || 0);

            if ((customerCode || supplierCode) && (receipt > 0 || payment > 0)) {
                hasValidEntry = true;
            }
        });

        if (!hasValidEntry) {
            e.preventDefault();
            alert('Please add at least one entry with customer or supplier and receipt or payment greater than 0.');
            return false;
        }
    });
})();
</script>
@endsection
