@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">{{ $medicine->name }} - Stock Details</h2>
        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Back to Stock List</a>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-teal text-white rounded-top-4">
                    <tr>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                        <th>Quantity</th>
                                                <th>price</th>
                                                                        <th>ptr</th>
                                                                                                <th>expiry date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($batches as $batch)
    <tr>
        <td>{{ $batch->batch_number }}</td>
        <td>{{ $batch->supplier->name ?? 'N/A' }}</td>
        <td>{{ $batch->pivot->quantity }}</td>
        <td>{{ $batch->pivot->price }}</td>
        <td>{{ $batch->pivot->ptr }}</td>
        <td>{{ $batch->pivot->expiry_date }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted">No batches found for this medicine.</td>
    </tr>
@endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-teal {
        color: #00838f;
    }
    .bg-teal {
        background-color: #00838f;
    }
</style>
@endsection
