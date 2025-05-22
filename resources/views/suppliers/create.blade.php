@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center" style="color: #00838f;">Add New Supplier</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="card shadow-sm p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Supplier Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter supplier name">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" class="form-control" value="{{ old('contact_number') }}" placeholder="Enter contact number">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email address">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter address">{{ old('address') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn" style="background-color: #00838f; color: white;">Save Supplier</button>
            </div>
        </div>
    </form>
</div>
@endsection
