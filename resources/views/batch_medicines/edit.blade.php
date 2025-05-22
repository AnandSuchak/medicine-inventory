@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-teal">Edit Medicines for Batch #{{ $batch->batch_number }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batches.medicines.update', $batch->id) }}" method="POST">
        @csrf
        @method('POST')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>PTR</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $index => $medicine)
                <tr>
                    <td>
                        {{ $medicine->name }}
                        <input type="hidden" name="medicines[{{ $index }}][medicine_id]" value="{{ $medicine->id }}">
                    </td>
                    <td>
                        <input type="number" name="medicines[{{ $index }}][quantity]" value="{{ $medicine->pivot->quantity }}" min="1" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="medicines[{{ $index }}][price]" value="{{ $medicine->pivot->price }}" class="form-control price-input" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="medicines[{{ $index }}][ptr]" value="{{ $medicine->pivot->ptr }}" class="form-control ptr-input" required>
                    </td>
                    <td>
                        <a href="{{ route('batches.medicines.remove', ['batch' => $batch->id, 'medicine' => $medicine->id]) }}" class="btn btn-danger btn-sm">Remove</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-teal">Save Changes</button>
        </div>
    </form>
</div>

<style>
    .text-teal { color: #00838f; }
    .btn-teal { background-color: #00838f; color: white; }
    .btn-teal:hover { background-color: #006064; }
</style>

<script>
    // PTR >= Price Validation before form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;

        document.querySelectorAll('tbody tr').forEach(row => {
            let priceInput = row.querySelector('.price-input');
            let ptrInput = row.querySelector('.ptr-input');

            if (priceInput && ptrInput) {
                let price = parseFloat(priceInput.value);
                let ptr = parseFloat(ptrInput.value);

                if (ptr < price) {
                    alert('PTR price cannot be less than selling price!');
                    valid = false;
                }
            }
        });

        if (!valid) e.preventDefault();
    });
</script>
@endsection
