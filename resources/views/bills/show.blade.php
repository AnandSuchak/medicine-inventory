@extends('layouts.app') {{-- Adjust your layout file as needed --}}

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Bill Details - #{{ $bill->bill_number }}</h4>
            <a href="{{ route('bills.index') }}" class="btn btn-secondary btn-sm">Back to Bills</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Customer:</strong> {{ $bill->customer->name ?? 'N/A' }} <br>
                    <strong>Phone:</strong> {{ $bill->customer->phone ?? 'N/A' }} <br>
                    <strong>Address:</strong> {{ $bill->customer->address ?? 'N/A' }}
                </div>
                <div class="col-md-6 text-md-end">
                    <strong>Bill Date:</strong> {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }} <br>
                    <strong>Bill Number:</strong> {{ $bill->bill_number }} <br>
                    <strong>Status:</strong> {{ ucfirst($bill->status) }}
                </div>
            </div>

            <hr>

            <h5>Items on Bill</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>GST %</th>
                        <th>GST Amount</th>
                        <th>Sub Total</th>
                        <th>Total After Tax</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($bill->billItems as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->medicine->name ?? 'N/A' }} (Batch: {{ $item->batch->batch_number ?? 'N/A' }})</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->gst_rate_percentage, 2) }}%</td>
                            <td>₹{{ number_format($item->item_gst_amount, 2) }}</td>
                            <td>₹{{ number_format($item->sub_total, 2) }}</td>
                            <td>₹{{ number_format($item->total_amount_after_tax, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-6 offset-md-6 text-end">
                    <p><strong>Sub Total (Before Tax):</strong> ₹{{ number_format($bill->sub_total_before_tax, 2) }}</p>
                    <p><strong>Total GST Amount:</strong> ₹{{ number_format($bill->total_gst_amount, 2) }}</p>
                    <p><strong>Discount Amount:</strong> ₹{{ number_format($bill->discount_amount, 2) }}</p>
                    <h4><strong>Net Amount:</strong> ₹{{ number_format($bill->net_amount, 2) }}</h4>
                </div>
            </div>

            @if ($bill->notes)
                <div class="mt-4">
                    <strong>Notes:</strong>
                    <p>{{ $bill->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection