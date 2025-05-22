@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center text-teal mb-4">Edit Bill - {{ $bill->bill_number }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('bills.update', $bill->id) }}">
        @csrf
        @method('PUT')

        <div class="card shadow-sm p-4">
            <div class="form-group mb-3">
                <label for="customer_id" class="form-label text-teal">Select Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Select a Customer</option>
                    @foreach(App\Models\Customer::all() as $customer)
                        <option value="{{ $customer->id }}" {{ $bill->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <h4 class="mt-4 text-teal">Medicines</h4>

            <div class="row fw-bold text-muted mb-2">
                <div class="col-md-4">Medicine</div>
                <div class="col-md-2">Quantity</div>
                <div class="col-md-2">MRP</div>
                <div class="col-md-2">Total</div>
                <div class="col-md-2">Action</div>
            </div>

            <div id="medicine-items">
                @foreach($bill->items as $i => $item)
                @php 
                    $stockQty = $stock[$item->medicine_id]->quantity ?? 0;
                @endphp
                <div class="row mb-2">
                    <div class="col-md-4">
                        <select name="medicines[{{ $i }}][medicine_id]" class="form-control medicine-select" required>
                            <option value="">-- Select Medicine --</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" 
                                    data-price="{{ $medicine->price }}" 
                                    data-stock="{{ $stock[$medicine->id]->quantity ?? 0 }}"
                                    {{ $item->medicine_id == $medicine->id ? 'selected' : '' }}>
                                    {{ $medicine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="medicines[{{ $i }}][quantity]" class="form-control quantity" min="1" value="{{ $item->quantity }}" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="medicines[{{ $i }}][unit_price]" class="form-control price" value="{{ number_format($item->unit_price, 2) }}" readonly required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="medicines[{{ $i }}][total]" class="form-control total" value="{{ number_format($item->quantity * $item->unit_price, 2) }}" readonly required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-item">X</button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-item">+ Add Medicine</button>

            <div class="mb-3">
                <label for="status" class="form-label text-teal">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="Ordered" {{ $bill->status == 'Ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="Paid" {{ $bill->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('bills.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-teal">Update Bill</button>
            </div>
        </div>
    </form>
</div>

<script>
    let index = {{ $bill->items->count() }};

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('medicine-select')) {
            let row = e.target.closest('.row');
            let priceField = row.querySelector('.price');
            let quantityField = row.querySelector('.quantity');
            let totalField = row.querySelector('.total');

            let selected = e.target.options[e.target.selectedIndex];
            let price = parseFloat(selected.getAttribute('data-price') || 0);
            let stock = parseInt(selected.getAttribute('data-stock') || 0);

            quantityField.value = 1;
            priceField.value = price.toFixed(2);
            totalField.value = price.toFixed(2);

            if (stock > 0) {
                quantityField.setAttribute('max', stock);
            } else {
                quantityField.removeAttribute('max');
            }
        }

        if (e.target.classList.contains('quantity')) {
            let row = e.target.closest('.row');
            let quantity = parseInt(e.target.value);
            let price = parseFloat(row.querySelector('.price').value);
            let totalField = row.querySelector('.total');

            let medicineSelect = row.querySelector('.medicine-select');
            let max = parseInt(medicineSelect.options[medicineSelect.selectedIndex].getAttribute('data-stock') || 0);

            if (quantity > max) {
                e.target.value = max;
                quantity = max;
            }

            totalField.value = (quantity * price).toFixed(2);
        }
    });

    document.getElementById('add-item').addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'row mb-2';
        row.innerHTML = `
            <div class="col-md-4">
                <select name="medicines[${index}][medicine_id]" class="form-control medicine-select" required>
                    <option value="">-- Select Medicine --</option>
                    @foreach($medicines as $medicine)
                        @php $stockQty = $stock[$medicine->id]->quantity ?? 0; @endphp
                        <option value="{{ $medicine->id }}" 
                            data-price="{{ $medicine->price }}" 
                            data-stock="{{ $stockQty }}">
                            {{ $medicine->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="medicines[${index}][quantity]" class="form-control quantity" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="medicines[${index}][unit_price]" class="form-control price" value="0.00" readonly required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="medicines[${index}][total]" class="form-control total" value="0.00" readonly required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">X</button>
            </div>
        `;
        document.getElementById('medicine-items').appendChild(row);
        index++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.row').remove();
        }
    });
</script>

<style>
    .text-teal {
        color: #00838f;
    }
    .btn-teal {
        background-color: #00838f;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
    }
    .btn-teal:hover {
        background-color: #006064;
    }
</style>
@endsection
