@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <h2 class="mb-4 text-center text-teal fw-bold">Batch Details</h2>

            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="card-title text-teal mb-4">Batch: {{ $batch->batch_number }}</h3>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Supplier:</div>
                        <div class="col-sm-8">{{ $batch->supplier->name ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Purchase Date:</div>
                        <div class="col-sm-8">{{ $batch->purchase_date->format('d F, Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Status:</div>
                        <div class="col-sm-8">
                            <span class="badge {{
                                $batch->status == 'active' ? 'bg-success' :
                                ($batch->status == 'inactive' ? 'bg-warning text-dark' :
                                ($batch->status == 'completed' ? 'bg-info' : 'bg-secondary'))
                            }} rounded-pill px-3 py-2">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Created At:</div>
                        <div class="col-sm-8">{{ $batch->created_at->format('d M, Y H:i A') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-semibold">Last Updated:</div>
                        <div class="col-sm-8">{{ $batch->updated_at->format('d M, Y H:i A') }}</div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('batches.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i> Back to Batches
                        </a>
                        <a href="{{ route('batches.edit', $batch->id) }}" class="btn btn-teal-primary btn-lg rounded-pill px-4">
                            <i class="bi bi-pencil-square me-2"></i> Edit Batch Info
                        </a>
                    </div>
                </div>
            </div>

            {{-- MEDICINES IN THIS BATCH SECTION --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
                <h3 class="text-teal fw-bold mb-0">Medicines in this Batch ({{ $batch->medicines->count() }})</h3>
                {{-- THIS IS THE "ADD/UPDATE MEDICINES" BUTTON --}}
                <a href="{{ route('batches.medicines.create', $batch->id) }}" class="btn btn-teal-primary btn-sm rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i> Add/Update Medicines
                </a>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-0">
                    @if($batch->medicines->isEmpty())
                        <div class="text-center p-5">
                            <h4 class="text-muted mb-3">No medicines added to this batch yet.</h4>
                            <p class="text-muted">You can add medicines by clicking the "Add/Update Medicines" button above.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-teal text-white rounded-top-4">
                                    <tr>
                                        <th scope="col" class="py-3 ps-4 rounded-top-start-4">Medicine Name</th>
                                        <th scope="col" class="py-3">Quantity</th>
                                        <th scope="col" class="py-3">Purchase Price</th>
                                        <th scope="col" class="py-3">PTR</th>
                                        <th scope="col" class="py-3">GST (%)</th>
                                        <th scope="col" class="py-3">Expiry Date</th>
                                        <th scope="col" class="py-3 text-center rounded-top-end-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batch->medicines as $medicine)
                                        <tr class="border-bottom">
                                            <td class="ps-4 py-3 fw-semibold">{{ $medicine->name }} ({{ $medicine->unit }})</td>
                                            <td>{{ $medicine->pivot->quantity }}</td>
                                            <td>₹{{ number_format($medicine->pivot->price, 2) }}</td>
                                            <td>₹{{ number_format($medicine->pivot->ptr, 2) }}</td>
                                            <td>{{ $medicine->pivot->gst_percent }}%</td>
                                            <td>{{ \Carbon\Carbon::parse($medicine->pivot->expiry_date)->format('M Y') }}</td>
                                            <td class="text-center py-3">
                                                <div class="d-flex justify-content-center gap-2">
                                                    {{-- Link to edit a specific batch medicine --}}
                                                    <a href="{{ route('batches.medicines.edit', $batch->id) }}#medicine_{{ $medicine->id }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Edit</a>
                                                    <form action="{{ route('batches.medicines.remove', [$batch->id, $medicine->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to remove this medicine from the batch?')">Remove</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection