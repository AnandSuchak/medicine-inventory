@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Medicines List</h2>
        <a href="{{ route('medicines.create') }}" class="btn btn-teal">+ Add New Medicine</a>
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
                        <th>HSN Code</th>
                        <th>Quantity</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->hsn_code }}</td>
                            <td>{{ $medicine->quantity }}</td>
                            <td class="text-center">
                                <a href="{{ route('medicines.show', $medicine->id) }}" class="btn btn-outline-info btn-sm">View</a>
                                <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No medicines found.</td>
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
