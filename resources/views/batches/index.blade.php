@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal fw-bold mb-0">Batch Management</h2>
        <a href="{{ route('batches.create') }}" class="btn btn-teal-primary btn-lg rounded-pill shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Create New Batch
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-0">
            @if($batches->isEmpty())
                <div class="text-center p-5">
                    <h4 class="text-muted mb-3">No batches found.</h4>
                    <p class="text-muted">It looks like your batch list is empty. Start by creating a new batch!</p>
                    <a href="{{ route('batches.create') }}" class="btn btn-teal-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i> Create First Batch
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="bg-teal text-white rounded-top-4">
                            <tr>
                                <th scope="col" class="py-3 ps-4 rounded-top-start-4">Batch Number</th>
                                <th scope="col" class="py-3">Supplier</th>
                                <th scope="col" class="py-3">Purchase Date</th>
                                <th scope="col" class="py-3">Status</th>
                                <th scope="col" class="py-3 text-center">Medicines Count</th>
                                <th scope="col" class="py-3 text-center rounded-top-end-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batches as $batch)
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3 fw-semibold">{{ $batch->batch_number }}</td>
                                    <td>{{ $batch->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ $batch->purchase_date->format('d M, Y') }}</td>
                                    <td>
                                        <span class="badge {{
                                            $batch->status == 'active' ? 'bg-success' :
                                            ($batch->status == 'inactive' ? 'bg-warning text-dark' :
                                            ($batch->status == 'completed' ? 'bg-info' : 'bg-secondary'))
                                        }} rounded-pill px-3 py-2">
                                            {{ ucfirst($batch->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $batch->medicines_count }}</td>
                                    <td class="text-center py-3">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">View</a>
                                            <a href="{{ route('batches.edit', $batch->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Edit</a>
                                            <a href="{{ route('batches.medicines.create', $batch->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">Add Medicines</a>
                                            <form action="{{ route('batches.destroy', $batch->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this batch?')">Delete</button>
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

    <div class="d-flex justify-content-center mt-5">
        {{ $batches->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection