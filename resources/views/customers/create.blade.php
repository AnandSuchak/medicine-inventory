@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Add New Customer</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm p-4">
            <div class="mb-3">
                <label for="name" class="form-label">Customer Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn" style="background-color: #00838f; color: white;">Save Customer</button>
            </div>
        </div>
    </form>
</div>
@endsection
