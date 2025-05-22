@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Batch List</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-teal">+ Add New Customer</a>
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
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->address }}</td>
                            <td class="text-center">
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $customers->links() }} <!-- Pagination links -->
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
