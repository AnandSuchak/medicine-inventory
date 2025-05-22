@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Customer Details</h2>

    <div class="card shadow-sm p-4">
        <div class="mb-3">
            <label for="name" class="form-label">Customer Name</label>
            <p>{{ $customer->name }}</p>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <p>{{ $customer->email }}</p>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <p>{{ $customer->phone }}</p>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <p>{{ $customer->address }}</p>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
