@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Current Stock</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-teal text-white rounded-top-4">
                    <tr>
                        <th>Medicine</th>
                        <th>Total Stock</th>
                    </tr>
                </thead>
                <tbody>
 @foreach ($medicines as $medicine)
                        <?php
                            // Calculate total stock by summing quantities from the Batch table
                            $totalStock = \App\Models\Batch::where('medicine_id', $medicine->id)->sum('quantity');
                        ?>
                        <tr>
                            <td>
                                <!-- Link to the detailed stock page for the clicked medicine -->
                                <a href="{{ route('stocks.show', $medicine->id) }}" class="text-teal">
                                    {{ $medicine->name }}
                                </a>
                            </td>
                            <td>{{ $totalStock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-teal {
        color: #00838f;
    }
    .bg-teal {
        background-color: #00838f;
    }
</style>
@endsection
