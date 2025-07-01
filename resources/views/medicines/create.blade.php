@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h2 class="mb-4 text-center text-teal fw-bold">Add New Medicine</h2>

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
                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Medicine Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg rounded-pill" value="{{ old('name') }}" placeholder="e.g., Paracetamol, Amoxicillin" required>
                        </div>

                        <div class="mb-4">
                            <label for="hsn_code" class="form-label fw-semibold">HSN Code <span class="text-danger">*</span></label>
                            <input type="text" name="hsn_code" id="hsn_code" class="form-control form-control-lg rounded-pill" value="{{ old('hsn_code') }}" placeholder="e.g., 3004.90.11" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" class="form-control rounded-3" rows="3" placeholder="Brief description or usage instructions">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="unit" class="form-label fw-semibold">Unit <span class="text-danger">*</span></label> {{-- Changed from Price --}}
                            <input type="text" name="unit" id="unit" class="form-control form-control-lg rounded-pill" value="{{ old('unit') }}" placeholder="e.g., Tablet, Bottle, Box" required>
                        </div>

                        {{-- Removed the 'quantity' field as it's not directly on the medicines table --}}

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-save me-2"></i> Save Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection