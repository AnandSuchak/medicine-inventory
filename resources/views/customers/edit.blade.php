@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Edit Customer</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm p-4">
            <div class="mb-3">
                <label for="name" class="form-label">Customer Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $customer->email) }}">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn" style="background-color: #00838f; color: white;">Update Customer</button>
            </div>
        </div>
    </form>
</div>
@endsection
