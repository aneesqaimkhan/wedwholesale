@extends('tenant.layouts.admin')

@section('title', 'Create Sales Invoice')
@section('page-title', 'Create Sales Invoice')

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
        .compact-form .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #6D2D9D;
            margin: 8px 0 5px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #e1e5e9;
        }
        .compact-form .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
        }
        .compact-form .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .compact-form .error {
            font-size: 11px;
            margin-top: 2px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }
        .form-grid.full-width {
            grid-column: 1 / -1;
        }
        .form-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }
        .form-section {
            margin-bottom: 15px;
        }
        .form-section-title {
            font-size: 12px;
            font-weight: 600;
            color: #6D2D9D;
            margin: 8px 0 5px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #e1e5e9;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            margin-bottom: 12px;
            border-bottom: 1px solid #e1e5e9;
        }
        .invoice-header-left {
            flex: 1;
            text-align: left;
        }
        .invoice-header-center {
            flex: 1;
            text-align: center;
        }
        .invoice-header-right {
            flex: 1;
            text-align: right;
        }
        .invoice-header-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }
        .invoice-header-value {
            font-size: 14px;
            font-weight: 600;
            color: #6D2D9D;
        }
        .table th,
        .table td {
            padding: 4px 2px;
        }
        #items_table td {
            padding: 2px;
        }
        .rate-type-buttons {
            display: flex;
            gap: 3px;
            margin-top: 3px;
        }
        .rate-type-btn {
            padding: 2px 6px;
            font-size: 10px;
            border: 1px solid #6D2D9D;
            background: white;
            color: #6D2D9D;
            cursor: pointer;
            border-radius: 3px;
        }
        .rate-type-btn:hover {
            background: #6D2D9D;
            color: white;
        }
        .rate-type-btn.active {
            background: #6D2D9D;
            color: white;
        }
        @media (max-width: 1200px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .form-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-grid-4 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card compact-form">
        <form method="POST" action="{{ route_include_subdirectory('sales_invoices.store') }}">
            @csrf

            <!-- Hidden fields for auto-generated values -->
            <input type="hidden" name="invoice_no" value="{{ $nextInvoiceNo }}">
            <input type="hidden" name="previous_balance" value="0" id="previous_balance">

            <!-- Invoice Information Header -->
            <div class="invoice-header">
                <div class="invoice-header-left">
                    <!-- Last Price Information Label (left side) -->
                    <div id="last_price_info" style="margin-top: 0; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: none;">
                        <div id="last_price_content" style="font-size: 12px; color: #666; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;"></div>
                    </div>
                </div>
                <div class="invoice-header-right">
                    <div style="display: inline-flex; gap: 24px; align-items: flex-end; justify-content: flex-end;">
                        <div style="text-align: right;">
                            <div class="invoice-header-label">Invoice No</div>
                            <div class="invoice-header-value">{{ $nextInvoiceNo }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div class="invoice-header-label">Previous Balance</div>
                            <div class="invoice-header-value" id="balance_display">0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer, Date, Remarks in one row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 0px;">
                <div class="form-group">
                    <label>Search Customer *</label>
                    <input list="customer_list" class="form-control" id="customer_input" placeholder="Type customer name to search" required>
                    <datalist id="customer_list">
                        @foreach($customers as $c)
                            <option value="{{ $c->name }}" data-id="{{ $c->id }}" data-name="{{ $c->name }}" data-mobile="{{ $c->mobile ?? '' }}" data-address="{{ $c->address ?? '' }}"></option>
                        @endforeach
                    </datalist>
                    <input type="hidden" name="customer_code" id="customer_code" required>
                    <input type="hidden" name="customer_name" id="customer_name" required>
                    <input type="hidden" name="address" id="address">
                </div>
                <div class="form-group">
                    <label>Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="Remarks" required>
                </div>
            </div>

            <div class="card" style="padding:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <strong style="font-size: 12px;">Items</strong>
                        <span id="current_stock_badge" style="display:none; font-size:11px; color:#6D2D9D; background:#f4effa; border:1px solid #e4d7f6; padding:2px 6px; border-radius:4px;">
                            Stock: 0 box
                        </span>
                    </div>
                    <button type="button" class="btn" id="add_row" style="padding: 6px 16px; font-size: 12px;">+ Add Item</button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="items_table" style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th style="width:25%; font-size: 11px;">
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <div style="display: flex; gap: 4px;">
                                        <span style="font-size: 10px; width: 30%;">Code</span>
                                        <span style="font-size: 10px; width: 20%;">#</span>
                                        <span style="font-size: 10px; flex: 1;">Product</span>
                                    </div>
                                </div>
                            </th>
                            <th style="font-size: 11px; width: 10%;">Pack</th>
                            <th style="font-size: 11px; width: 8%;">Box</th>
                            <th style="font-size: 11px; width: 10%;">Rate</th>
                            <th style="font-size: 11px; width: 8%;">B/Box</th>
                            <th style="font-size: 11px; width: 10%;">STX</th>
                            <th style="font-size: 11px; width: 10%;">Disc</th>
                            <th style="font-size: 11px; width: 12%;">Net</th>
                            <th style="font-size: 11px; width: 5%;"></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <th class="text-right">Total (<span id="total_items_count">0</span> items)</th>
                            <th id="total_pack">0</th>
                            <th id="total_box">0</th>
                            <th></th>
                            <th id="total_b_per_box">0.00</th>
                            <th id="total_stx">0.00</th>
                            <th id="total_discount">0.00</th>
                            <th id="total_net">0.00</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div style="display: flex; gap: 8px; margin-top: 10px; justify-content: flex-end;">
                <button type="submit" class="btn" id="submit_btn" style="padding: 6px 16px; font-size: 12px;">Save Invoice</button>
            </div>
        </form>
    </div>


    <script>
        // Function to fetch and display last price information
        function checkAndFetchLastPrice() {
            const customerCode = document.getElementById('customer_code').value;
            
            // Find the first row with a selected product
            const itemRows = document.querySelectorAll('#items_table tbody tr');
            let productCode = null;
            
            for (let i = 0; i < itemRows.length; i++) {
                const rowProductCode = itemRows[i].querySelector('.product_code');
                if (rowProductCode && rowProductCode.value && rowProductCode.value.trim() !== '') {
                    productCode = rowProductCode.value;
                    break;
                }
            }
            
            const lastPriceInfo = document.getElementById('last_price_info');
            const lastPriceContent = document.getElementById('last_price_content');
            
            // Hide if customer or product not selected
            if (!customerCode || !productCode) {
                lastPriceInfo.style.display = 'none';
                return;
            }
            
            // Fetch last price from API
            const url = '{{ route_include_subdirectory("sales_invoices.get_last_price", ["subdomain" => request()->route("subdomain")]) }}';
            fetch(url + '?customer_code=' + encodeURIComponent(customerCode) + '&product_code=' + encodeURIComponent(productCode))
                .then(response => response.json())
                .then(data => {
                    console.log('Last price data:', data); // Debug log
                    if (data.success && data.data) {
                        const sales = data.data.sales;
                        const purchase = data.data.purchase;
                        let content = '';
                        
                        if (sales) {
                            content += `
                                <span style="padding-left: 10px; border-left: 1px solid #dee2e6;"><strong style="color: #6D2D9D;">Sales:</strong> Rate: ${sales.rate.toFixed(2)} | Discount: ${sales.discount.toFixed(2)}% | Net: ${sales.net_amount.toFixed(2)} | Invoice #${sales.invoice_no} (${sales.invoice_date})</span>
                            `;
                        }
                        
                        if (purchase) {
                            content += `
                                <span style="padding-left: 10px; border-left: 1px solid #dee2e6;"><strong style="color: #6D2D9D;">Purchase:</strong> Rate: ${purchase.rate.toFixed(2)} | Discount: ${purchase.discount.toFixed(2)}% | Net: ${purchase.net_amount.toFixed(2)} | Invoice #${purchase.invoice_no} (${purchase.invoice_date})</span>
                            `;
                        }
                        
                        if (!sales && !purchase) {
                            content = '<span style="color: #999;">No previous invoice found for this customer-product combination.</span>';
                        }
                        
                        lastPriceContent.innerHTML = content;
                        lastPriceInfo.style.display = 'block';
                    } else {
                        lastPriceContent.innerHTML = '<span style="color: #999;">No previous invoice found for this customer-product combination.</span>';
                        lastPriceInfo.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching last price:', error);
                    lastPriceInfo.style.display = 'none';
                });
        }

        // Function to fetch and display current stock (boxes) for a product
        function fetchAndShowCurrentStock(productCode) {
            const badge = document.getElementById('current_stock_badge');
            if (!productCode) {
                if (badge) badge.style.display = 'none';
                return;
            }
            const url = '{{ route_include_subdirectory("sales_invoices.get_current_stock", ["subdomain" => request()->route("subdomain")]) }}';
            fetch(url + '?product_code=' + encodeURIComponent(productCode))
                .then(response => response.json())
                .then(data => {
                    if (!badge) return;
                    if (data.success && data.data) {
                        const boxes = parseFloat(data.data.stock_boxes || 0);
                        badge.textContent = 'Stock: ' + (isNaN(boxes) ? 0 : boxes.toFixed(0)) + ' box' + (boxes == 1 ? '' : 'es');
                        badge.style.display = 'inline-block';
                    } else {
                        badge.textContent = 'Stock: 0 boxes';
                        badge.style.display = 'inline-block';
                    }
                })
                .catch(() => {
                    if (badge) badge.style.display = 'none';
                });
        }
        
        // Clear error styling when fields are corrected
        document.addEventListener('DOMContentLoaded', function() {
            // Clear invoice date error on change
            const invoiceDate = document.querySelector('input[name="invoice_date"]');
            if (invoiceDate) {
                invoiceDate.addEventListener('input', function() {
                    this.style.borderColor = '';
                });
            }
            
            // Clear customer error on change
            const customerInput = document.getElementById('customer_input');
            if (customerInput) {
                customerInput.addEventListener('input', function() {
                    this.style.borderColor = '';
                });
            }
            
            // Clear remarks error on change
            const remarks = document.querySelector('input[name="remarks"]');
            if (remarks) {
                remarks.addEventListener('input', function() {
                    this.style.borderColor = '';
                });
            }
        });
        
        // Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let errors = [];
            
            // Validate Invoice Date
            const invoiceDate = document.querySelector('input[name="invoice_date"]');
            if (!invoiceDate.value || invoiceDate.value.trim() === '') {
                errors.push('Invoice Date is required');
                invoiceDate.style.borderColor = '#dc3545';
                invoiceDate.style.borderWidth = '2px';
            } else {
                invoiceDate.style.borderColor = '';
                invoiceDate.style.borderWidth = '';
            }
            
            // Validate Customer
            const customerCode = document.getElementById('customer_code');
            const customerName = document.getElementById('customer_name');
            const customerInput = document.getElementById('customer_input');
            if (!customerCode.value || customerCode.value.trim() === '' || 
                !customerName.value || customerName.value.trim() === '') {
                errors.push('Customer is required');
                customerInput.style.borderColor = '#dc3545';
                customerInput.style.borderWidth = '2px';
            } else {
                customerInput.style.borderColor = '';
                customerInput.style.borderWidth = '';
            }
            
            // Validate Remarks
            const remarks = document.querySelector('input[name="remarks"]');
            if (!remarks.value || remarks.value.trim() === '') {
                errors.push('Remarks is required');
                remarks.style.borderColor = '#dc3545';
                remarks.style.borderWidth = '2px';
            } else {
                remarks.style.borderColor = '';
                remarks.style.borderWidth = '';
            }
            
            // Validate Items - at least one item with box quantity > 0 and product selected
            const itemRows = document.querySelectorAll('#items_table tbody tr');
            let validItemsCount = 0;
            let totalNetAmount = 0;
            
            itemRows.forEach((row, index) => {
                const productCode = row.querySelector('.product_code');
                const productName = row.querySelector('.product_name');
                const box = row.querySelector('.box');
                const netAmount = row.querySelector('.net_amount');
                
                if (productCode && productName && box && netAmount) {
                    const boxValue = parseFloat(box.value || 0);
                    const netValue = parseFloat(netAmount.value || 0);
                    
                    // Check if item has product and box quantity
                    if (productCode.value && productCode.value.trim() !== '' &&
                        productName.value && productName.value.trim() !== '' &&
                        boxValue > 0) {
                        validItemsCount++;
                    }
                    
                    // Sum up net amounts
                    totalNetAmount += netValue;
                }
            });
            
            if (validItemsCount === 0) {
                errors.push('At least one item with product and box quantity is required');
                // Highlight items table
                const itemsTable = document.getElementById('items_table');
                if (itemsTable) {
                    itemsTable.style.border = '2px solid #dc3545';
                }
            } else if (totalNetAmount <= 0) {
                errors.push('At least one item must have a net amount greater than 0');
                // Highlight items table
                const itemsTable = document.getElementById('items_table');
                if (itemsTable) {
                    itemsTable.style.border = '2px solid #dc3545';
                }
            } else {
                const itemsTable = document.getElementById('items_table');
                if (itemsTable) {
                    itemsTable.style.border = '';
                }
            }
            
            // Show errors or submit form
            if (errors.length > 0) {
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                // Scroll to first error field
                const firstError = invoiceDate.style.borderColor === 'rgb(220, 53, 69)' ? invoiceDate :
                                  customerInput.style.borderColor === 'rgb(220, 53, 69)' ? customerInput :
                                  remarks.style.borderColor === 'rgb(220, 53, 69)' ? remarks : null;
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => firstError.focus(), 300);
                }
                return false;
            }
            
            // All validations passed, submit the form
            this.submit();
        });
    </script>

    <datalist id="product_list">
        @foreach($products as $p)
            <option value="{{ $p->product_name }}" 
                data-code="{{ $p->product_code }}" 
                data-name="{{ $p->product_name }}" 
                data-id="{{ $p->product_id }}"
                data-pack="{{ $p->packing ?? '' }}" 
                data-boxpcs="{{ $p->pcs_in_box ?? 0 }}" 
                data-n-price="{{ $p->n_price_box ?? 0 }}"
                data-t-price="{{ $p->t_price_box ?? 0 }}"
                data-r-price="{{ $p->r_price_box ?? 0 }}"
                data-default-rate="{{ $p->default_rate_type ?? 'N' }}"
                data-sales-tax="{{ $p->sales_tax ?? 0 }}"></option>
        @endforeach
    </datalist>
    <datalist id="product_code_list">
        @foreach($products as $p)
            <option value="{{ $p->product_code }}" 
                data-code="{{ $p->product_code }}" 
                data-name="{{ $p->product_name }}" 
                data-id="{{ $p->product_id }}"
                data-pack="{{ $p->packing ?? '' }}" 
                data-boxpcs="{{ $p->pcs_in_box ?? 0 }}" 
                data-n-price="{{ $p->n_price_box ?? 0 }}"
                data-t-price="{{ $p->t_price_box ?? 0 }}"
                data-r-price="{{ $p->r_price_box ?? 0 }}"
                data-default-rate="{{ $p->default_rate_type ?? 'N' }}"
                data-sales-tax="{{ $p->sales_tax ?? 0 }}"></option>
        @endforeach
    </datalist>

    <template id="row_tpl">
        <tr>
            <td style="vertical-align: top;">
                <div style="display: flex; gap: 4px;">
                    <input list="product_code_list" class="form-control product_code_input" placeholder="Code" style="padding: 5px 4px; font-size: 11px; height: 30px; width: 30%;">
                    <select class="form-control product_rate_type_select" style="padding: 5px 4px; font-size: 11px; height: 30px; width: 20%;" title="Rate Type">
                        <option value="N">N</option>
                        <option value="T">T</option>
                        <option value="R">R</option>
                    </select>
                    <input list="product_list" class="form-control product_input" placeholder="Product" style="padding: 5px 4px; font-size: 11px; height: 30px; flex: 1;">
                </div>
                <input type="hidden" name="items[IDX][product_code]" class="product_code">
                <input type="hidden" name="items[IDX][product_name]" class="product_name">
                <input type="hidden" name="items[IDX][selected_rate_type]" class="selected_rate_type" value="N">
            </td>
            <td><input type="text" name="items[IDX][pack]" class="form-control pack" readonly style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;"></td>
            <td><input type="number" name="items[IDX][box]" class="form-control box" value="0" style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;"></td>
            <td><input type="number" step="0.01" name="items[IDX][rate]" class="form-control rate" value="0" style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;"></td>
            <td><input type="number" step="0.01" name="items[IDX][b_per_box]" class="form-control b_per_box" value="0" style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;"></td>
            <td><input type="number" step="0.01" min="0" name="items[IDX][stx]" class="form-control stx" value="0" placeholder="Amount" readonly style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;" title="Sales Tax Amount"></td>
            <td><input type="number" step="0.01" min="0" max="100" name="items[IDX][discount]" class="form-control discount" value="0" placeholder="%" style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;" title="Discount Percentage (0-100)"></td>
            <td><input type="number" step="0.01" name="items[IDX][net_amount]" class="form-control net_amount" value="0" readonly style="padding: 5px 3px; font-size: 12px; height: 30px; width: 100%;"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove_row" style="padding: 4px 8px; font-size: 11px;">X</button></td>
        </tr>
    </template>

    <script>
        (function() {
            const customers = document.getElementById('customer_list').options;
            const customerInput = document.getElementById('customer_input');
            const codeEl = document.getElementById('customer_code');
            const nameEl = document.getElementById('customer_name');
            const addrEl = document.getElementById('address');
            customerInput && customerInput.addEventListener('change', function() {
                const val = this.value;
                for (let i = 0; i < customers.length; i++) {
                    if (customers[i].value === val) {
                        codeEl.value = customers[i].dataset.id || '';
                        nameEl.value = customers[i].dataset.name || '';
                        addrEl.value = customers[i].dataset.address || '';
                        // Check if product is selected to fetch last price
                        checkAndFetchLastPrice();
                        break;
                    }
                }
            });


            let idx = 0;
            const addRowBtn = document.getElementById('add_row');
            const tableBody = document.querySelector('#items_table tbody');
            const rowTpl = document.getElementById('row_tpl').innerHTML;

            function recalcRow(tr) {
                const box = parseFloat(tr.querySelector('.box').value || 0);
                const rate = parseFloat(tr.querySelector('.rate').value || 0);
                const stxInput = tr.querySelector('.stx');
                const discPercent = parseFloat(tr.querySelector('.discount').value || 0);

                // Calculate: (box * rate) + STX (absolute) - (Discount % of subtotal)
                const subtotal = box * rate;
                
                // Use the STX value from the field (preserves manual edits)
                // STX is auto-filled from product's sales_tax value when product is selected
                let stxAmount = parseFloat(stxInput.value || 0);
                
                // Calculate discount amount as percentage of subtotal
                const discountAmount = (subtotal * discPercent) / 100;
                
                // Calculate net amount: subtotal + STX (absolute) - Discount Amount
                const net = subtotal + stxAmount - discountAmount;
                tr.querySelector('.net_amount').value = Math.max(0, net).toFixed(2);
                recalcTotal();
            }

            function recalcTotal() {
                let totalNet = 0;
                let totalBox = 0;
                let totalPack = 0;
                let totalBPerBox = 0;
                let totalStx = 0;
                let totalDiscountAmount = 0;
                let itemsCount = 0;
                
                document.querySelectorAll('#items_table tbody tr').forEach(row => {
                    const boxInput = row.querySelector('.box');
                    const packInput = row.querySelector('.pack');
                    const rateInput = row.querySelector('.rate');
                    const bPerBoxInput = row.querySelector('.b_per_box');
                    const stxInput = row.querySelector('.stx');
                    const discountInput = row.querySelector('.discount');
                    const netInput = row.querySelector('.net_amount');
                    const productCode = row.querySelector('.product_code');
                    
                    const box = parseFloat(boxInput?.value || 0);
                    const rate = parseFloat(rateInput?.value || 0);
                    const discountPercent = parseFloat(discountInput?.value || 0);
                    const subtotal = box * rate;
                    
                    // Count all items with product selected (regardless of box quantity)
                    if (productCode && productCode.value && productCode.value.trim() !== '') {
                        itemsCount++;
                    }
                    
                    // Sum all values (including empty rows for display)
                    totalBox += box;
                    totalPack += parseFloat(packInput?.value || 0);
                    totalBPerBox += parseFloat(bPerBoxInput?.value || 0);
                    totalStx += parseFloat(stxInput?.value || 0);
                    
                    // Calculate discount amount (percentage of subtotal) - same calculation as in recalcRow
                    if (box > 0 && rate > 0 && !isNaN(discountPercent) && discountPercent >= 0) {
                        const discountAmount = (subtotal * discountPercent) / 100;
                        if (!isNaN(discountAmount) && isFinite(discountAmount)) {
                            totalDiscountAmount += discountAmount;
                        }
                    }
                    
                    totalNet += parseFloat(netInput?.value || 0);
                });
                
                const totalNetEl = document.getElementById('total_net');
                const totalBoxEl = document.getElementById('total_box');
                const totalPackEl = document.getElementById('total_pack');
                const totalBPerBoxEl = document.getElementById('total_b_per_box');
                const totalStxEl = document.getElementById('total_stx');
                const totalDiscountEl = document.getElementById('total_discount');
                const totalItemsCountEl = document.getElementById('total_items_count');
                
                if (totalNetEl) totalNetEl.innerText = totalNet.toFixed(2);
                if (totalBoxEl) totalBoxEl.innerText = totalBox.toFixed(0);
                if (totalPackEl) totalPackEl.innerText = totalPack.toFixed(0);
                if (totalBPerBoxEl) totalBPerBoxEl.innerText = totalBPerBox.toFixed(2);
                if (totalStxEl) totalStxEl.innerText = totalStx.toFixed(2);
                if (totalDiscountEl) {
                    totalDiscountEl.innerText = totalDiscountAmount.toFixed(2);
                    console.log('Total discount updated:', totalDiscountAmount.toFixed(2));
                } else {
                    console.error('total_discount element not found');
                }
                if (totalItemsCountEl) totalItemsCountEl.innerText = itemsCount;
            }

            function wireRow(tr) {
                const products = document.getElementById('product_list').options;
                const productCodes = document.getElementById('product_code_list').options;
                const pInput = tr.querySelector('.product_input');
                const pCodeInput = tr.querySelector('.product_code_input');
                const pCode = tr.querySelector('.product_code');
                const pName = tr.querySelector('.product_name');
                const rateTypeSelect = tr.querySelector('.product_rate_type_select');
                const pack = tr.querySelector('.pack');
                const rate = tr.querySelector('.rate');
                const bpb = tr.querySelector('.b_per_box');
                const selectedRateType = tr.querySelector('.selected_rate_type');

                function selectProduct(productOption) {
                    const code = productOption.dataset.code || '';
                    const name = productOption.dataset.name || '';
                    const nPrice = parseFloat(productOption.getAttribute('data-n-price') || 0);
                    const tPrice = parseFloat(productOption.getAttribute('data-t-price') || 0);
                    const rPrice = parseFloat(productOption.getAttribute('data-r-price') || 0);
                    const defaultRate = (productOption.getAttribute('data-default-rate') || 'N').toUpperCase();
                    const salesTax = parseFloat(productOption.getAttribute('data-sales-tax') || 0);

                    pCode.value = code;
                    pName.value = name;
                    pCodeInput.value = code;
                    // Show only product name in product field
                    pInput.value = name;
                    pack.value = productOption.dataset.pack || '';
                    bpb.value = productOption.dataset.boxpcs || 0;
                    
                    // Set STX field with product's sales_tax value (absolute amount)
                    const stxInput = tr.querySelector('.stx');
                    if (salesTax > 0) {
                        stxInput.value = salesTax.toFixed(2);
                    } else {
                        stxInput.value = '0';
                    }
                    
                    // Trigger recalculation if box/rate already have values
                    const currentBox = parseFloat(tr.querySelector('.box').value || 0);
                    const currentRate = parseFloat(tr.querySelector('.rate').value || 0);
                    if (currentBox > 0 && currentRate > 0) {
                        recalcRow(tr);
                    } else {
                        // Even if box/rate are empty, recalc to set STX properly
                        recalcRow(tr);
                    }
                    
                    // Auto-add a new blank row after product is selected
                    // Check if there's already a blank row (row without product)
                    const allRows = document.querySelectorAll('#items_table tbody tr');
                    let hasBlankRow = false;
                    
                    for (let i = 0; i < allRows.length; i++) {
                        const row = allRows[i];
                        const rowProductCode = row.querySelector('.product_code');
                        if (!rowProductCode || !rowProductCode.value || rowProductCode.value.trim() === '') {
                            hasBlankRow = true;
                            break;
                        }
                    }
                    
                    // If no blank row exists, add a new one
                    if (!hasBlankRow && code && name) {
                        addRow();
                    }

                    // Update rate type select
                    rateTypeSelect.innerHTML = '';
                    if (nPrice > 0) {
                        const opt = document.createElement('option');
                        opt.value = 'N';
                        opt.textContent = 'N';
                        if (defaultRate === 'N') opt.selected = true;
                        rateTypeSelect.appendChild(opt);
                    }
                    if (tPrice > 0) {
                        const opt = document.createElement('option');
                        opt.value = 'T';
                        opt.textContent = 'T';
                        if (defaultRate === 'T') opt.selected = true;
                        rateTypeSelect.appendChild(opt);
                    }
                    if (rPrice > 0) {
                        const opt = document.createElement('option');
                        opt.value = 'R';
                        opt.textContent = 'R';
                        if (defaultRate === 'R') opt.selected = true;
                        rateTypeSelect.appendChild(opt);
                    }

                    // Set rate based on product's default rate type
                    let selectedPrice = 0;
                    if (defaultRate === 'N' && nPrice > 0) {
                        selectedPrice = nPrice;
                        selectedRateType.value = 'N';
                        rateTypeSelect.value = 'N';
                    } else if (defaultRate === 'T' && tPrice > 0) {
                        selectedPrice = tPrice;
                        selectedRateType.value = 'T';
                        rateTypeSelect.value = 'T';
                    } else if (defaultRate === 'R' && rPrice > 0) {
                        selectedPrice = rPrice;
                        selectedRateType.value = 'R';
                        rateTypeSelect.value = 'R';
                    } else {
                        // Fallback: use first available price
                        if (nPrice > 0) {
                            selectedPrice = nPrice;
                            selectedRateType.value = 'N';
                            rateTypeSelect.value = 'N';
                        } else if (tPrice > 0) {
                            selectedPrice = tPrice;
                            selectedRateType.value = 'T';
                            rateTypeSelect.value = 'T';
                        } else if (rPrice > 0) {
                            selectedPrice = rPrice;
                            selectedRateType.value = 'R';
                            rateTypeSelect.value = 'R';
                        }
                    }
                    
                    rate.value = selectedPrice.toFixed(2);
                    recalcRow(tr);

                    // Show current stock for the selected product (boxes only)
                    fetchAndShowCurrentStock(code);
                }

                // Product name search
                pInput.addEventListener('change', function() {
                    const val = this.value;
                    for (let i = 0; i < products.length; i++) {
                        if (products[i].value === val) {
                            selectProduct(products[i]);
                            // Clear items table error border when product is selected
                            const itemsTable = document.getElementById('items_table');
                            if (itemsTable) {
                                itemsTable.style.border = '';
                            }
                            // Check if customer is selected to fetch last price
                            checkAndFetchLastPrice();
                            break;
                        }
                    }
                    // If no exact match, clear current stock badge
                    const code = tr.querySelector('.product_code')?.value || '';
                    fetchAndShowCurrentStock(code);
                });

                // Product code search
                pCodeInput.addEventListener('change', function() {
                    const codeVal = this.value.trim().toUpperCase();
                    if (!codeVal) return;
                    
                    // Search in product code list first
                    for (let i = 0; i < productCodes.length; i++) {
                        const code = (productCodes[i].value || '').trim().toUpperCase();
                        if (code === codeVal) {
                            selectProduct(productCodes[i]);
                            // Clear items table error border when product is selected
                            const itemsTable = document.getElementById('items_table');
                            if (itemsTable) {
                                itemsTable.style.border = '';
                            }
                            // Check if customer is selected to fetch last price
                            checkAndFetchLastPrice();
                            break;
                        }
                    }
                    // Always try to show stock for whatever code is in the input
                    fetchAndShowCurrentStock(codeVal);
                });

                // Rate type select change - update rate when dropdown changes
                rateTypeSelect.addEventListener('change', function() {
                    const selectedRate = this.value;
                    if (!selectedRate) {
                        return;
                    }
                    
                    // Get prices from the selected product
                    const productOption = Array.from(products).find(p => p.value === pInput.value) || 
                                        Array.from(productCodes).find(p => p.value === pCodeInput.value);
                    
                    if (!productOption) {
                        // If no product selected, don't update rate
                        return;
                    }
                    
                    const nPrice = parseFloat(productOption.getAttribute('data-n-price') || 0);
                    const tPrice = parseFloat(productOption.getAttribute('data-t-price') || 0);
                    const rPrice = parseFloat(productOption.getAttribute('data-r-price') || 0);

                    // Update rate based on selected rate type
                    if (selectedRate === 'N') {
                        rate.value = nPrice > 0 ? nPrice.toFixed(2) : 0;
                        selectedRateType.value = 'N';
                    } else if (selectedRate === 'T') {
                        rate.value = tPrice > 0 ? tPrice.toFixed(2) : 0;
                        selectedRateType.value = 'T';
                    } else if (selectedRate === 'R') {
                        rate.value = rPrice > 0 ? rPrice.toFixed(2) : 0;
                        selectedRateType.value = 'R';
                    }
                    
                    recalcRow(tr);
                });

                // Recalculate when box, rate, or discount changes
                tr.querySelectorAll('.box, .rate, .discount').forEach(el => {
                    el.addEventListener('input', () => {
                        recalcRow(tr);
                        // Clear items table error border when box is entered
                        const itemsTable = document.getElementById('items_table');
                        if (itemsTable && el.classList.contains('box')) {
                            const boxValue = parseFloat(el.value || 0);
                            if (boxValue > 0) {
                                itemsTable.style.border = '';
                            }
                        }
                    });
                    // Also add change event for discount to ensure it updates
                    if (el.classList.contains('discount')) {
                        el.addEventListener('change', () => {
                            recalcRow(tr);
                        });
                    }
                });
                
                // STX is readonly - calculated automatically from product's sales_tax

                tr.querySelector('.remove_row').addEventListener('click', function() {
                    tr.remove();
                    recalcTotal();
                    
                    // Ensure there's always at least one blank row
                    const allRows = document.querySelectorAll('#items_table tbody tr');
                    let hasBlankRow = false;
                    
                    for (let i = 0; i < allRows.length; i++) {
                        const row = allRows[i];
                        const rowProductCode = row.querySelector('.product_code');
                        if (!rowProductCode || !rowProductCode.value || rowProductCode.value.trim() === '') {
                            hasBlankRow = true;
                            break;
                        }
                    }
                    
                    // If no blank row exists after removal, add a new one
                    if (!hasBlankRow) {
                        addRow();
                    }
                });
            }

            function addRow() {
                const html = rowTpl.replaceAll('IDX', idx++);
                const temp = document.createElement('tbody');
                temp.innerHTML = html.trim();
                const tr = temp.firstElementChild;
                wireRow(tr);
                tableBody.appendChild(tr);
                
                // Clear items table error border when new row is added
                const itemsTable = document.getElementById('items_table');
                if (itemsTable) {
                    itemsTable.style.border = '';
                }
            }

            addRowBtn.addEventListener('click', addRow);
            addRow();
        })();
    </script>
@endsection


