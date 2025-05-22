@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Medicine</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('medicines.update', $medicine->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $medicine->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>HSN Code</label>
            <input type="text" name="hsn_code" value="{{ $medicine->hsn_code }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $medicine->description }}</textarea>
        </div>


        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" value="{{ $medicine->quantity }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
