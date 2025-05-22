@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Batch List</h2>
        <a href="{{ route('batches.create') }}" class="btn btn-teal">+ Add New Batch</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-teal text-white rounded-top-4">
                    <tr>
                        <th>ID</th>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                         <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
    <tr>
        <td>{{ $batch->id }}</td>
        <td>
    <a href="{{ route('batches.show', $batch->id) }}" class="text-teal text-decoration-underline">
        {{ $batch->batch_number }}
    </a>
</td>

        <td>{{ $batch->supplier->name ?? 'N/A' }}</td>
        <td>{{ $batch->created_at->format('d-m-Y h:i A') }}</td> <!-- Formatted Date -->
        <td class="text-center">
            @if($batch->medicines_count > 0)
            <a href="{{ route('batches.medicines.edit', ['batch' => $batch->id]) }}" class="btn btn-primary">Edit Batch</a>   
            @else
                <a href="{{ route('batches.medicines.create', ['batch' => $batch->id]) }}" class="btn btn-success">Add Medicine</a>

            @endif

            <form action="{{ route('batches.destroy', $batch->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted">No batches found.</td>
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
    .btn-teal {
        background-color: #00838f;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
    }
    .btn-teal:hover {
        background-color: #006064;
    }
    .bg-teal {
        background-color: #00838f;
    }
</style>
@endsection
