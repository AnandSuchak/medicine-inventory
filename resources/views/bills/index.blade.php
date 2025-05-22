@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-teal">All Bills</h2>
        <a href="{{ route('bills.create') }}" class="btn btn-teal">Generate New Bill</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-teal text-white rounded-top-4">
                    <tr>
                        <th>Bill No</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                    <tr class="align-middle">
                        <td>
                            <a href="{{ route('bills.show', $bill->id) }}" class="text-decoration-none text-teal fw-semibold">
                                {{ $bill->bill_number }}
                            </a>
                        </td>
                        <td>{{ $bill->customer->name ?? 'N/A' }}</td>
                        <td>₹{{ number_format($bill->total_amount, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($bill->status == 'Ordered') bg-warning 
                                @elseif($bill->status == 'Completed') bg-success 
                                @else bg-secondary 
                                @endif">
                                {{ $bill->status }}
                            </span>
                        </td>
                        <td>{{ $bill->created_at->format('d M Y, h:i A') }}</td>
                        <td class="text-center">
                            <a href="{{ route('bills.show', $bill->id) }}" class="btn btn-outline-info btn-sm me-1">View</a>
                            <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No bills found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination (Optional) --}}
    @if($bills instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-3">
        {{ $bills->links() }}
    </div>
    @endif
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
    .bg-teal {
        background-color: #00838f;
    }
    .rounded-top-4 {
        border-radius: 0.5rem 0.5rem 0 0;
    }
    tbody tr:hover {
        background-color: #e0f2f1;
    }
</style>
@endsection
