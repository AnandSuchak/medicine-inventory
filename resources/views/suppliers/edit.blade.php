@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Edit Supplier</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="card shadow-sm p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Supplier Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" class="form-control" value="{{ old('contact_number', $supplier->contact_number) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $supplier->address) }}">
                </div>
            </div>

            <div class="mb-3 d-flex align-items-center">
                <label for="statusToggle" class="custom-label mb-0">Active Status</label>
                <div class="form-check form-switch ms-auto mb-0">
                    <!-- Hidden input to handle unchecked status (inactive) -->
                    <input type="hidden" name="status" value="inactive">
                    <input class="form-check-input" type="checkbox" id="statusToggle" name="status" value="active"
                        {{ isset($supplier) && $supplier->status === 'active' ? 'checked' : '' }}>
                </div>
            </div>


            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn" style="background-color: #00838f; color: white;">Update Supplier</button>
            </div>
        </div>
    </form>
</div>

<style>

    .custom-label {
        margin-right: 15px; /* Increase as needed */
    }

    .form-switch {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-check-input[type="checkbox"] {
        width: 2.5em;
        height: 1.4em;
        background-color: #ddd;
        border-radius: 1em;
        position: relative;
        appearance: none;
        outline: none;
        transition: background-color 0.3s ease;
    }

    .form-check-input:checked {
        background-color: #00838f;
    }

    .form-check-input::before {
        content: "";
        position: absolute;
        top: 0.1em;
        left: 0.1em;
        width: 1.2em;
        height: 1.2em;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s ease;
    }

    .form-check-input:checked::before {
        transform: translateX(1.1em);
    }
</style>
@endsection
