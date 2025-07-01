@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <h2 class="mb-4 text-center text-teal fw-bold">Stock Details for {{ $medicineDetails->name }}</h2>

            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="card-title text-teal mb-4">{{ $medicineDetails->name }} ({{ $medicineDetails->unit }})</h3>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Description:</div>
                        <div class="col-sm-8">{{ $medicineDetails->description ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Overall Stock:</div>
                        <div class="col-sm-8"><span class="badge bg-primary fs-6 px-3 py-2">{{ $totalStock }}</span> {{ $medicineDetails->unit }}</div>
                    </div>

                    <hr class="my-4">

                    <h4 class="text-teal mb-3">Batches Containing This Medicine ({{ $medicineDetails->batches->count() }})</h4>

                    @if($medicineDetails->batches->isEmpty())
                        <div class="text-center p-4">
                            <p class="text-muted">No batches found for this medicine.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-light-teal rounded-top-4">
                                    <tr>
                                        <th scope="col" class="py-3 ps-4 rounded-top-start-4">Batch Number</th>
                                        <th scope="col" class="py-3">Quantity</th>
                                        <th scope="col" class="py-3">Purchase Date</th>
                                        <th scope="col" class="py-3">Expiry Date</th>
                                        <th scope="col" class="py-3">Price</th>
                                        <th scope="col" class="py-3">PTR</th>
                                        <th scope="col" class="py-3 text-center rounded-top-end-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicineDetails->batches as $batch)
                                        <tr class="border-bottom">
                                            <td class="ps-4 py-3 fw-semibold">
                                                <a href="{{ route('batches.show', $batch->id) }}" class="text-teal text-decoration-none">
                                                    {{ $batch->batch_number }}
                                                </a>
                                            </td>
                                            <td>{{ $batch->pivot->quantity }}</td>
                                            <td>{{ $batch->purchase_date->format('d M, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($batch->pivot->expiry_date)->format('M Y') }}</td>
                                            <td>₹{{ number_format($batch->pivot->price, 2) }}</td>
                                            <td>₹{{ number_format($batch->pivot->ptr, 2) }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">View Batch</a>
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
                <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to All Stock
                </a>
            </div>

        </div>
    </div>
</div>
@endsection