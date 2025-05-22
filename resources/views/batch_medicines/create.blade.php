@extends('layouts.app')

@section('content')
<div class="container my-4 p-4 batch-medicine-container shadow-sm rounded bg-light">
    <h2 class="mb-4 text-teal fw-bold">Add Medicines to Batch: {{ $batch->batch_number }}</h2>

    @if(session('success'))
        <div class="alert alert-success rounded-pill">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-pill">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batches.medicines.store', $batch->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <table class="table table-bordered align-middle bg-white">
            <thead class="table-teal text-white">
                <tr>
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
                        <select name="medicines[0][medicine_id]" class="form-select" required>
                            <option value="">-- Select Medicine --</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a medicine.</div>
                    </td>
                    <td>
                        <input type="number" name="medicines[0][quantity]" class="form-control" min="1" required>
                        <div class="invalid-feedback">Quantity must be at least 1.</div>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="medicines[0][price]" class="form-control" min="0" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="medicines[0][ptr]" class="form-control" min="0" required>
                        <div class="invalid-feedback">Please enter a valid PTR.</div>
                    </td>
                    <td>
                        <input type="date" name="medicines[0][expiry_date]" class="form-control" required>
                        <div class="invalid-feedback">Please select an expiry date.</div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger remove-row" aria-label="Remove row">&times;</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="add-row" class="btn btn-secondary mb-3">+ Add Medicine</button>

        <div class="d-flex justify-content-between">
            <a href="{{ route('batches.index') }}" class="btn btn-outline-secondary">Back</a>
            <button type="submit" class="btn btn-teal fw-semibold">Save Medicines</button>
        </div>
    </form>
</div>

<style>
    /* Teal theme colors */
    .text-teal {
        color: #00838f !important;
    }
    .btn-teal {
        background-color: #00838f;
        color: white;
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-teal:hover,
    .btn-teal:focus {
        background-color: #006064;
        color: white;
    }

    .table-teal {
        background-color: #00838f;
    }

    /* Container styling */
    .batch-medicine-container {
        background: #f9fdfd;
        border: 1px solid #c9dcdc;
    }

    /* Buttons */
    .btn-secondary {
        background-color: #607d8b;
        border-color: #546e7a;
        color: white;
        transition: background-color 0.3s ease;
    }
    .btn-secondary:hover,
    .btn-secondary:focus {
        background-color: #455a64;
        border-color: #37474f;
        color: white;
    }

    /* Table */
    table.table {
        box-shadow: 0 2px 6px rgb(0 131 143 / 0.2);
    }

    /* Form inputs focus */
    .form-select:focus,
    .form-control:focus {
        border-color: #00838f;
        box-shadow: 0 0 5px #00838f80;
        outline: none;
    }

    /* Remove button */
    .remove-row {
        padding: 0.3rem 0.6rem;
        font-weight: bold;
        font-size: 1.25rem;
        line-height: 1;
    }
</style>

<script>
    // Add new row with incremented index for array inputs
    let rowIdx = 1;
    document.getElementById('add-row').addEventListener('click', function() {
        const medicines = @json($medicines);
        const options = medicines.map(med => `<option value="${med.id}">${med.name}</option>`).join('');

        const newRow = `
            <tr>
                <td>
                    <select name="medicines[${rowIdx}][medicine_id]" class="form-select" required>
                        <option value="">-- Select Medicine --</option>
                        ${options}
                    </select>
                    <div class="invalid-feedback">Please select a medicine.</div>
                </td>
                <td>
                    <input type="number" name="medicines[${rowIdx}][quantity]" class="form-control" min="1" required>
                    <div class="invalid-feedback">Quantity must be at least 1.</div>
                </td>
                <td>
                    <input type="number" step="0.01" name="medicines[${rowIdx}][price]" class="form-control" min="0" required>
                    <div class="invalid-feedback">Please enter a valid price.</div>
                </td>
                <td>
                    <input type="number" step="0.01" name="medicines[${rowIdx}][ptr]" class="form-control" min="0" required>
                    <div class="invalid-feedback">Please enter a valid PTR.</div>
                </td>
                <td>
                    <input type="date" name="medicines[${rowIdx}][expiry_date]" class="form-control" required>
                    <div class="invalid-feedback">Please select an expiry date.</div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger remove-row" aria-label="Remove row">&times;</button>
                </td>
            </tr>
        `;
        document.getElementById('medicine-rows').insertAdjacentHTML('beforeend', newRow);
        rowIdx++;
    });

    // Remove row on click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Bootstrap validation
    (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endsection
