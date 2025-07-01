@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h2 class="mb-4 text-center text-teal fw-bold">Medicine Details</h2>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="card-title text-teal mb-4">{{ $medicine->name }}</h3>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">HSN Code:</div>
                        <div class="col-sm-8">{{ $medicine->hsn_code ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Description:</div>
                        <div class="col-sm-8">{{ $medicine->description ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Unit:</div> {{-- Changed from Price --}}
                        <div class="col-sm-8">{{ $medicine->unit ?? 'N/A' }}</div>
                    </div>

                    {{-- Removed the 'Current Quantity' display as it's not directly on the medicines table --}}

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i> Back to List
                        </a>
                        <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-teal-primary btn-lg rounded-pill px-4">
                            <i class="bi bi-pencil-square me-2"></i> Edit Medicine
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection