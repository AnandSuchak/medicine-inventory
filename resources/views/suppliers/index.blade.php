@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Supplier List</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-teal">+ Add New Supplier</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-teal text-white rounded-top-4">
                    <tr>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact_number }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ ucfirst($supplier->status) }}</td>
                            <td class="text-center">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No suppliers found.</td>
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
