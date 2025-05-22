@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Add New Medicine</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('medicines.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="card shadow-sm p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Medicine Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter medicine name">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="hsn_code" class="form-label">HSN Code</label>
                    <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="{{ old('hsn_code') }}" required placeholder="Enter HSN code">
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter description">{{ old('description') }}</textarea>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" min="0" required placeholder="e.g. 100">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn" style="background-color: #00838f; color: white;">Save Medicine</button>
            </div>
        </div>
    </form>
</div>
@endsection
