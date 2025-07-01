@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4 text-center text-teal fw-bold">Current Medicine Stock Overview</h2>

            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-0">
                    @if($stockItems->isEmpty())
                        <div class="text-center p-5">
                            <h4 class="text-muted mb-3">No medicines found or no stock recorded.</h4>
                            <p class="text-muted">Start by adding medicines and then creating batches for them.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-teal text-white rounded-top-4">
                                    <tr>
                                        <th scope="col" class="py-3 ps-4 rounded-top-start-4">Medicine Name</th>
                                        <th scope="col" class="py-3">Unit</th>
                                        <th scope="col" class="py-3 text-center">Total Stock Quantity</th>
                                        <th scope="col" class="py-3 text-center rounded-top-end-4">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockItems as $medicine)
                                        <tr class="border-bottom">
                                            <td class="ps-4 py-3 fw-semibold">{{ $medicine->name }}</td>
                                            <td>{{ $medicine->unit }}</td>
                                            <td class="text-center">{{ $medicine->total_stock_quantity }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('stocks.show', $medicine->id) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">View Batches</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection