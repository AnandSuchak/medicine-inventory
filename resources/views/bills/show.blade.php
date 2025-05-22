@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-teal">Bill #{{ $bill->bill_number }}</h2>
        <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary">← Back to All Bills</a>
    </div>

    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $bill->customer->name ?? 'N/A' }}</p>
            <p>
                <strong>Status:</strong>
                <span class="badge 
                    @if($bill->status == 'Ordered') bg-warning 
                    @elseif($bill->status == 'Completed') bg-success 
                    @else bg-secondary 
                    @endif">
                    {{ $bill->status }}
                </span>
            </p>
            <p><strong>Date:</strong> {{ $bill->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="bg-teal text-white">
                    <tr>
                        <th>Medicine</th>
                        <th>Batch Code</th>
                        <th>Expiry Date</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill->medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->pivot->batch_code ?? 'N/A' }}</td>
                        <td>
                            {{ $medicine->pivot->expiry_date 
                                ? \Carbon\Carbon::parse($medicine->pivot->expiry_date)->format('M Y') 
                                : 'N/A' }}
                        </td>
                        <td>{{ $medicine->pivot->quantity }}</td>
                        <td>₹{{ number_format($medicine->pivot->unit_price, 2) }}</td>
                        <td>₹{{ number_format($medicine->pivot->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end text-teal fs-5">Grand Total</th>
                        <th class="fs-5 text-teal">₹{{ number_format($bill->total_amount, 2) }}</th>
                    </tr>
                </tfoot>
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
    .btn-outline-secondary {
        border-radius: 6px;
    }
</style>
@endsection
