@extends('tenant.layouts.admin')

@section('title', 'Edit Receipt/Payment')
@section('page-title', 'Edit Receipt/Payment')

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
</style>

<div class="card compact-form">
    <form method="POST" action="{{ route_include_subdirectory('receipt_payments.update', ['subdomain' => request()->route('subdomain'), 'receipt_payment' => $receipt_payment->id]) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="payment_from">Payment From *</label>
            <select id="payment_from" name="payment_from" class="form-control" required onchange="updateEntityList()">
                <option value="">Select...</option>
                <option value="customer" {{ old('payment_from', $receipt_payment->payment_from) == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="salesman" {{ old('payment_from', $receipt_payment->payment_from) == 'salesman' ? 'selected' : '' }}>Salesman</option>
            </select>
            @error('payment_from')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Search Entity</label>
            <input list="entity_list" class="form-control" id="entity_input" placeholder="Type name to search" value="{{ old('entity_name', $receipt_payment->entity_name) }}">
            <datalist id="entity_list"></datalist>
            <input type="hidden" name="entity_code" id="entity_code" value="{{ old('entity_code', $receipt_payment->entity_code) }}">
            <input type="hidden" name="entity_name" id="entity_name" value="{{ old('entity_name', $receipt_payment->entity_name) }}">
            @error('entity_code')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
            @error('entity_name')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="amount">Amount *</label>
                <input type="number" step="0.01" id="amount" name="amount" class="form-control" value="{{ old('amount', $receipt_payment->amount) }}" placeholder="Enter amount" required>
                @error('amount')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="payment_date">Payment Date *</label>
                <input type="date" id="payment_date" name="payment_date" class="form-control" value="{{ old('payment_date', $receipt_payment->payment_date->format('Y-m-d')) }}" required>
                @error('payment_date')
                    <div class="error" style="color: #dc3545;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" class="form-control" rows="3" placeholder="Enter remarks">{{ old('remarks', $receipt_payment->remarks) }}</textarea>
            @error('remarks')
                <div class="error" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <button type="submit" class="btn" style="padding: 6px 16px; font-size: 12px;">Update Receipt/Payment</button>
            <a href="{{ route_include_subdirectory('receipt_payments.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="background: #6c757d; padding: 6px 16px; font-size: 12px;">Cancel</a>
        </div>
    </form>
</div>

<datalist id="customer_list">
    @foreach($customers as $c)
        <option value="{{ $c->name }}" data-id="{{ $c->id }}" data-name="{{ $c->name }}"></option>
    @endforeach
</datalist>

<datalist id="salesman_list">
    @foreach($salesmen as $s)
        <option value="{{ $s->name }}" data-id="{{ $s->id }}" data-name="{{ $s->name }}"></option>
    @endforeach
</datalist>

<script>
    function updateEntityList() {
        const paymentFrom = document.getElementById('payment_from').value;
        const entityList = document.getElementById('entity_list');
        const sourceList = paymentFrom === 'customer' ? document.getElementById('customer_list') : document.getElementById('salesman_list');
        
        entityList.innerHTML = '';
        if (sourceList) {
            Array.from(sourceList.options).forEach(opt => {
                const newOpt = document.createElement('option');
                newOpt.value = opt.value;
                newOpt.setAttribute('data-id', opt.getAttribute('data-id'));
                newOpt.setAttribute('data-name', opt.getAttribute('data-name'));
                entityList.appendChild(newOpt);
            });
        }
    }

    // Initialize on page load
    updateEntityList();

    const entityInput = document.getElementById('entity_input');
    const entityCode = document.getElementById('entity_code');
    const entityName = document.getElementById('entity_name');
    
    entityInput.addEventListener('change', function() {
        const val = this.value;
        const entityList = document.getElementById('entity_list');
        for (let i = 0; i < entityList.options.length; i++) {
            if (entityList.options[i].value === val) {
                entityCode.value = entityList.options[i].getAttribute('data-id') || '';
                entityName.value = entityList.options[i].getAttribute('data-name') || '';
                break;
            }
        }
    });
</script>
@endsection

