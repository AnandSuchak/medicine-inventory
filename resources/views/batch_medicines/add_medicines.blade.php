@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-teal fw-bold">Add Medicines to Batch #{{ $batch->batch_number }}</h2>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batches.store-medicines', $batch->id) }}" method="POST" class="bg-white rounded-4 shadow-sm p-4">
        @csrf

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>PTR</th>
                        <th>Expiry Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="medicine-rows">
                    <tr>
                        <td>
                            <select name="medicine_id[]" class="form-select" required>
                                <option value="">Select Medicine</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="quantity[]" class="form-control" min="1" required></td>
                        <td><input type="number" step="0.01" name="price[]" class="form-control price-input" required></td>
                        <td><input type="number" step="0.01" name="ptr[]" class="form-control ptr-input" required></td>
                        <td><input type="date" name="expiry_date[]" class="form-control" required></td>
                        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-start mb-3">
            <button type="button" class="btn btn-outline-teal" id="add-row">+ Add Medicine</button>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-teal">Save Medicines</button>
        </div>
    </form>
</div>

<style>
    .text-teal { color: #00838f; }
    .btn-teal {
        background-color: #00838f;
        color: #fff;
        border: none;
    }
    .btn-teal:hover {
        background-color: #006064;
        color: #fff;
    }
    .btn-outline-teal {
        border: 1px solid #00838f;
        color: #00838f;
    }
    .btn-outline-teal:hover {
        background-color: #00838f;
        color: white;
    }
</style>

<script>
    // Add new row
    document.getElementById('add-row').addEventListener('click', function() {
        const row = `
            <tr>
                <td>
                    <select name="medicine_id[]" class="form-select" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="quantity[]" class="form-control" min="1" required></td>
                <td><input type="number" step="0.01" name="price[]" class="form-control price-input" required></td>
                <td><input type="number" step="0.01" name="ptr[]" class="form-control ptr-input" required></td>
                <td><input type="date" name="expiry_date[]" class="form-control" required></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row">Remove</button></td>
            </tr>
        `;
        document.getElementById('medicine-rows').insertAdjacentHTML('beforeend', row);
    });

    // Remove row button
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Prevent duplicate medicine selection
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'medicine_id[]') {
            let selectedValues = [];
            document.querySelectorAll('select[name="medicine_id[]"]').forEach(select => {
                selectedValues.push(select.value);
            });

            let duplicates = selectedValues.filter((item, index) => selectedValues.indexOf(item) !== index);

            if (duplicates.length > 0 && duplicates[0] !== "") {
                alert('This medicine is already added!');
                e.target.value = ''; // Reset selection
            }
        }
    });

    // PTR >= Price Validation before form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;

        document.querySelectorAll('#medicine-rows tr').forEach(row => {
            let priceInput = row.querySelector('.price-input');
            let ptrInput = row.querySelector('.ptr-input');

            if (priceInput && ptrInput) {
                let price = parseFloat(priceInput.value);
                let ptr = parseFloat(ptrInput.value);

                if (ptr < price) {
                    alert('PTR price cannot be less than selling price!');
                    valid = false;
                }
            }
        });

        if (!valid) e.preventDefault();
    });
</script>
@endsection
