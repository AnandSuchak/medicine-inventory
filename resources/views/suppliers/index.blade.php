@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal fw-bold mb-0">Supplier Directory</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-teal-primary btn-lg rounded-pill shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Add New Supplier
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-0">
            @if($suppliers->isEmpty())
                <div class="text-center p-5">
                    <h4 class="text-muted mb-3">No suppliers found.</h4>
                    <p class="text-muted">It looks like your supplier list is empty. Start by adding a new supplier!</p>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-teal-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i> Add First Supplier
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="bg-teal text-white rounded-top-4">
                            <tr>
                                <th scope="col" class="py-3 ps-4 rounded-top-start-4">Name</th>
                                <th scope="col" class="py-3">Phone</th>
                                <th scope="col" class="py-3">Email</th>
                                <th scope="col" class="py-3">GSTIN</th>
                                <th scope="col" class="py-3">Address</th>
                                <th scope="col" class="py-3 text-center rounded-top-end-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3 fw-semibold">{{ $supplier->name }}</td>
                                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                    <td>{{ $supplier->email ?? 'N/A' }}</td>
                                    <td>{{ $supplier->gstin ?? 'N/A' }}</td>
                                    <td>{{ $supplier->address ?? 'N/A' }}</td>
                                    <td class="text-center py-3">
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- The View button is now active --}}
                                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">View</a>
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Edit</a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</button>
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
        {{ $suppliers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection