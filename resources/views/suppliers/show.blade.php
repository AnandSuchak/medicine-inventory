@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h2 class="mb-4 text-center text-teal fw-bold">Supplier Details</h2>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="card-title text-teal mb-4">{{ $supplier->name }}</h3>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Email:</div>
                        <div class="col-sm-8">{{ $supplier->email ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Phone:</div>
                        <div class="col-sm-8">{{ $supplier->phone ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">GSTIN:</div>
                        <div class="col-sm-8">{{ $supplier->gstin ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Address:</div>
                        <div class="col-sm-8">{{ $supplier->address ?? 'N/A' }}</div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i> Back to List
                        </a>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-teal-primary btn-lg rounded-pill px-4">
                            <i class="bi bi-pencil-square me-2"></i> Edit Supplier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection