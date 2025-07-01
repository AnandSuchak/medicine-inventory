<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Purchase Bill Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 border-b pb-4">
                        <div>
                            <p class="font-semibold">Bill Number:</p>
                            <p>{{ $purchaseBill->batch_number }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Purchase Date:</p>
                            <p>{{ $purchaseBill->purchase_date->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Supplier:</p>
                            <p>{{ $purchaseBill->supplier->name }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Status:</p>
                            <p class="capitalize">{{ $purchaseBill->status }}</p>
                        </div>
                         <div>
                            <p class="font-semibold">Cash Discount:</p>
                            <p>{{ $purchaseBill->cash_discount_percentage }}%</p>
                        </div>
                         <div class="md:col-span-3">
                            <p class="font-semibold">Notes:</p>
                            <p>{{ $purchaseBill->notes ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <h4 class="text-xl font-semibold mb-4">Medicines in this Bill</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-2 px-3">Medicine</th>
                                    <th class="text-right py-2 px-3">Quantity</th>
                                    <th class="text-right py-2 px-3">Price</th>
                                    <th class="text-right py-2 px-3">Discount (%)</th>
                                    <th class="text-right py-2 px-3">GST (%)</th>
                                    <th class="text-right py-2 px-3">Expiry Date</th>
                                    <th class="text-right py-2 px-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($purchaseBill->medicines as $medicine)
                                    @php
                                        $price = $medicine->pivot->price;
                                        $quantity = $medicine->pivot->quantity;
                                        $discount = $medicine->pivot->discount_percentage;
                                        $gst = $medicine->pivot->gst_percent;

                                        $subtotalBeforeDiscount = $price * $quantity;
                                        $discountAmount = ($subtotalBeforeDiscount * $discount) / 100;
                                        $subtotalAfterDiscount = $subtotalBeforeDiscount - $discountAmount;
                                        $gstAmount = ($subtotalAfterDiscount * $gst) / 100;
                                        $lineTotal = $subtotalAfterDiscount + $gstAmount;
                                        $total += $lineTotal;
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-2 px-3">{{ $medicine->name }}</td>
                                        <td class="text-right py-2 px-3">{{ $quantity }}</td>
                                        <td class="text-right py-2 px-3">{{ number_format($price, 2) }}</td>
                                        <td class="text-right py-2 px-3">{{ $discount }}%</td>
                                        <td class="text-right py-2 px-3">{{ $gst }}%</td>
                                        <td class="text-right py-2 px-3">{{ \Carbon\Carbon::parse($medicine->pivot->expiry_date)->format('d-m-Y') }}</td>
                                        <td class="text-right py-2 px-3">{{ number_format($lineTotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                             <tfoot class="font-bold">
                                <tr>
                                    <td colspan="6" class="text-right py-2 px-3">Grand Total:</td>
                                    <td class="text-right py-2 px-3">{{ number_format($total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="mt-6">
                        <a href="{{ route('purchase_bills.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>