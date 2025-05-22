@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-teal">Batch #{{ $batch->batch_number }} Details</h2>

    <div class="mb-3">
        <strong>Supplier:</strong> {{ $batch->supplier->name ?? 'N/A' }}<br>
        <strong>Created At:</strong> {{ $batch->created_at->format('d-m-Y h:i A') }}
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="bg-teal text-white">
                    <tr>
                        <th>Medicine Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>PTR</th>
                        <th>Expiry Date</th> 
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->pivot->quantity }}</td>
                        <td>{{ $medicine->pivot->price }}</td>
                        <td>{{ $medicine->pivot->ptr }}</td>
                         <td>{{ \Carbon\Carbon::parse($medicine->pivot->expiry_date)->format('d-m-Y') }}</td> <!-- Formatted -->
  
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No medicines added in this batch.<a href="{{ route('batches.medicines.create', ['batch' => $batch->id]) }}" class="ms-2 text-teal fw-semibold">
             Add Medicines
        </a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('batches.index') }}" class="btn btn-secondary">Back to Batches</a>
    </div>
</div>

<style>
    .text-teal { color: #00838f; }
    .bg-teal { background-color: #00838f; }
</style>
@endsection
