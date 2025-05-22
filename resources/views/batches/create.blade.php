@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center text-teal mb-4">Add New Batch</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batches.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm p-4">
            <div class="form-group mb-3">
                <label for="supplier_id" class="form-label text-teal">Select Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-teal">Create Batch</button>
            </div>
        </div>
    </form>
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
</style>
@endsection
