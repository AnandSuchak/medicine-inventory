@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h2 class="mb-4 text-center text-teal fw-bold">Edit Batch</h2>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('batches.update', $batch->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="batch_number" class="form-label fw-semibold">Batch Number <span class="text-danger">*</span></label>
                            <input type="text" name="batch_number" id="batch_number" class="form-control form-control-lg rounded-pill" value="{{ old('batch_number', $batch->batch_number) }}" placeholder="e.g., BATCH-XYZ-123" required>
                        </div>

                        <div class="mb-4">
                            <label for="supplier_id" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_id" class="form-select form-select-lg rounded-pill" required>
                                <option value="">Select a Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $batch->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="purchase_date" class="form-label fw-semibold">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" id="purchase_date" class="form-control form-control-lg rounded-pill" value="{{ old('purchase_date', $batch->purchase_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select form-select-lg rounded-pill" required>
                                <option value="active" {{ old('status', $batch->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $batch->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="completed" {{ old('status', $batch->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('batches.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-arrow-clockwise me-2"></i> Update Batch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection