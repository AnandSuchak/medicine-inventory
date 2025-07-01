@extends('layouts.app') {{-- Adjust your layout file as needed --}}

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>All Bills</h2>
        <a href="{{ route('bills.create') }}" class="btn btn-primary">Generate New Bill</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if ($bills->isEmpty())
                <p>No bills generated yet.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Bill No.</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $bill)
                            <tr>
                                <td>{{ $bill->bill_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</td>
                                <td>{{ $bill->customer->name ?? 'N/A' }}</td>
                                <td>₹{{ number_format($bill->net_amount, 2) }}</td>
                                <td>{{ ucfirst($bill->status) }}</td>
                                <td>
                                    <a href="{{ route('bills.show', $bill->id) }}" class="btn btn-info btn-sm">View</a>
                                    {{-- NEW: Edit Button --}}
                                    <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-warning btn-sm ms-1">Edit</a>
                                    {{-- Add other actions like print if needed later --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    {{ $bills->links() }}
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
