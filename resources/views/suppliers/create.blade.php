@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h2 class="mb-4 text-center text-teal fw-bold">Add New Supplier</h2>

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
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg rounded-pill" value="{{ old('name') }}" placeholder="Enter supplier's full name" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg rounded-pill" value="{{ old('email') }}" placeholder="e.g., info@supplier.com">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-lg rounded-pill" value="{{ old('phone') }}" placeholder="e.g., +91 9876543210">
                        </div>

                        <div class="mb-4">
                            <label for="gstin" class="form-label fw-semibold">GSTIN</label>
                            <input type="text" name="gstin" id="gstin" class="form-control form-control-lg rounded-pill" value="{{ old('gstin') }}" placeholder="e.g., 27ABCDE1234F1Z5">
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Address</label>
                            <textarea name="address" id="address" class="form-control rounded-3" rows="4" placeholder="Full address including city, state, zip">{{ old('address') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-save me-2"></i> Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection