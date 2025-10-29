@extends('tenant.layouts.admin')

@section('title', 'Create Sales Invoice')
@section('page-title', 'Create Sales Invoice')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route_include_subdirectory('sales_invoices.store') }}">
            @csrf

            <div class="form-group">
                <label>Invoice No</label>
                <input type="number" name="invoice_no" class="form-control" value="{{ $nextInvoiceNo }}" required>
            </div>
            <div class="form-group">
                <label>Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label>Customer</label>
                <input list="customer_list" class="form-control" id="customer_input" placeholder="Code or Name">
                <datalist id="customer_list">
                    @foreach($customers as $c)
                        <option value="{{ $c->code }} - {{ $c->name }}" data-code="{{ $c->code }}" data-name="{{ $c->name }}" data-address="{{ $c->address }}"></option>
                    @endforeach
                </datalist>
            </div>
            <div class="form-group">
                <label>Customer Code</label>
                <input type="text" name="customer_code" id="customer_code" class="form-control">
            </div>
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" id="address" class="form-control">
            </div>

            <div class="form-group">
                <label>Salesman</label>
                <div style="display:flex; gap:10px;">
                    <input type="text" name="salesman_code" class="form-control" placeholder="Code" style="width:150px;">
                    <input type="text" name="salesman_name" class="form-control" placeholder="Name">
                </div>
            </div>

            <div class="form-group">
                <label>Remarks</label>
                <input type="text" name="remarks" class="form-control">
            </div>

            <div class="form-group">
                <label>Previous Balance</label>
                <input type="number" step="0.01" name="previous_balance" class="form-control" value="0">
            </div>

            <div class="card" style="padding:15px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <strong>Items</strong>
                    <button type="button" class="btn" id="add_row">+ Add Item</button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="items_table">
                        <thead>
                        <tr>
                            <th style="width:20%;">Product</th>
                            <th>Pack</th>
                            <th>Box</th>
                            <th>Pcs</th>
                            <th>Rate</th>
                            <th>B/Box</th>
                            <th>STX</th>
                            <th>Disc</th>
                            <th>Net</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <th colspan="8" class="text-right">Total</th>
                            <th id="total_net">0.00</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-success">Save Invoice</button>
            </div>
        </form>
    </div>

    <datalist id="product_list">
        @foreach($products as $p)
            <option value="{{ $p->code }} - {{ $p->name }}" data-code="{{ $p->code }}" data-name="{{ $p->name }}" data-pack="{{ $p->pack }}" data-boxpcs="{{ $p->box_pcs }}" data-rate="{{ $p->sale_rate }}"></option>
        @endforeach
    </datalist>

    <template id="row_tpl">
        <tr>
            <td>
                <input list="product_list" class="form-control product_input" placeholder="Code or Name">
                <input type="hidden" name="items[IDX][product_code]" class="product_code">
                <input type="hidden" name="items[IDX][product_name]" class="product_name">
            </td>
            <td><input type="text" name="items[IDX][pack]" class="form-control pack"></td>
            <td><input type="number" name="items[IDX][box]" class="form-control box" value="0"></td>
            <td><input type="number" name="items[IDX][pcs]" class="form-control pcs" value="0"></td>
            <td><input type="number" step="0.01" name="items[IDX][rate]" class="form-control rate" value="0"></td>
            <td><input type="number" step="0.01" name="items[IDX][b_per_box]" class="form-control b_per_box" value="0"></td>
            <td><input type="number" step="0.01" name="items[IDX][stx]" class="form-control stx" value="0"></td>
            <td><input type="number" step="0.01" name="items[IDX][discount]" class="form-control discount" value="0"></td>
            <td><input type="number" step="0.01" name="items[IDX][net_amount]" class="form-control net_amount" value="0" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm remove_row">X</button></td>
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
                        codeEl.value = customers[i].dataset.code || '';
                        nameEl.value = customers[i].dataset.name || '';
                        addrEl.value = customers[i].dataset.address || '';
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
                const pcs = parseFloat(tr.querySelector('.pcs').value || 0);
                const rate = parseFloat(tr.querySelector('.rate').value || 0);
                const bpb = parseFloat(tr.querySelector('.b_per_box').value || 0);
                const stx = parseFloat(tr.querySelector('.stx').value || 0);
                const disc = parseFloat(tr.querySelector('.discount').value || 0);

                const qty = (box * bpb) + pcs;
                let gross = qty * rate;
                gross += stx;
                gross -= disc;
                const net = Math.max(0, gross);
                tr.querySelector('.net_amount').value = net.toFixed(2);
                recalcTotal();
            }

            function recalcTotal() {
                let total = 0;
                document.querySelectorAll('.net_amount').forEach(i => {
                    total += parseFloat(i.value || 0);
                });
                document.getElementById('total_net').innerText = total.toFixed(2);
            }

            function wireRow(tr) {
                const products = document.getElementById('product_list').options;
                const pInput = tr.querySelector('.product_input');
                const pCode = tr.querySelector('.product_code');
                const pName = tr.querySelector('.product_name');
                const pack = tr.querySelector('.pack');
                const rate = tr.querySelector('.rate');
                const bpb = tr.querySelector('.b_per_box');

                pInput.addEventListener('change', function() {
                    const val = this.value;
                    for (let i = 0; i < products.length; i++) {
                        if (products[i].value === val) {
                            pCode.value = products[i].dataset.code || '';
                            pName.value = products[i].dataset.name || '';
                            pack.value = products[i].dataset.pack || '';
                            bpb.value = products[i].dataset.boxpcs || 0;
                            rate.value = products[i].dataset.rate || 0;
                            recalcRow(tr);
                            break;
                        }
                    }
                });

                tr.querySelectorAll('.box, .pcs, .rate, .b_per_box, .stx, .discount').forEach(el => {
                    el.addEventListener('input', () => recalcRow(tr));
                });

                tr.querySelector('.remove_row').addEventListener('click', function() {
                    tr.remove();
                    recalcTotal();
                });
            }

            function addRow() {
                const html = rowTpl.replaceAll('IDX', idx++);
                const temp = document.createElement('tbody');
                temp.innerHTML = html.trim();
                const tr = temp.firstElementChild;
                wireRow(tr);
                tableBody.appendChild(tr);
            }

            addRowBtn.addEventListener('click', addRow);
            addRow();
        })();
    </script>
@endsection


