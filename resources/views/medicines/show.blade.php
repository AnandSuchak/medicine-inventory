@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Medicine Details</h2>
        <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary">← Back to List</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-teal text-dark rounded-top-4">
            <h5 class="mb-0">{{ $medicine->name }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>HSN Code:</strong> {{ $medicine->hsn_code }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Quantity:</strong> {{ $medicine->quantity }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Description:</strong> {{ $medicine->description ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-teal {
        color: #00838f;
    }
    .bg-teal {
        background-color: #00838f;
    }
</style>
@endpush
