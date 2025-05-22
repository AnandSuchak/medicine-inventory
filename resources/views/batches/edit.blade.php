@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Edit Batch</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batches.update', $batch->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="card shadow-sm p-4">
            <div class="row">
                   <div class="form-group">
                    <label for="medicine_ids">Medicines</label>
                    <select name="medicine_ids[]" id="medicine_ids" class="form-control" multiple required>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" {{ in_array($medicine->id, $batch->medicines->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $medicine->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplier->id == $batch->supplier_id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                <label for="batch_number">Batch Number</label>
                <input type="text" class="form-control" id="batch_number" value="{{ $batch->batch_number }}" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control" required value="{{ $batch->expiry_date }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">MRP (₹)</label>
                    <input type="number" name="price" id="mrp" class="form-control" step="1" min="0" required value="{{ $batch->price }}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="0" required value="{{ $batch->quantity }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ptr" class="form-label">PTR (₹)</label>
                    <input type="number" name="ptr" id="ptr" class="form-control" step="1" min="0" value="{{ $batch->ptr }}">
                    @error('ptr')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn" style="background-color: #00838f; color: white;">Update Batch</button>
            </div>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const medicineSelect = document.getElementById('medicine_ids');
        const quantityDiv = document.getElementById('quantityDiv');
        
        // Add a new medicine row
        function addMedicineRow() {
            const medicineRow = document.createElement('div');
            medicineRow.classList.add('medicine-row');
            medicineRow.innerHTML = `
                <select name="medicine_ids[]" class="form-control">
                    <!-- Add medicine options dynamically here -->
                </select>
                <input type="number" name="quantity[]" class="form-control" placeholder="Enter quantity" required>
                <button type="button" class="btn btn-danger remove-row">Remove</button>
            `;
            quantityDiv.appendChild(medicineRow);
        }

        // Remove a medicine row
        quantityDiv.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.medicine-row').remove();
            }
        });
    });
</script>

@endsection
