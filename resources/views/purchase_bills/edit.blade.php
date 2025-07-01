<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Purchase Bill
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('purchase_bills.update', $purchaseBill->id) }}" method="POST" id="purchase-bill-form">
                        @csrf
                        @method('PUT')
                        {{-- Header Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="batch_number" class="block font-medium text-sm text-gray-700">Purchase Bill Number</label>
                                <input type="text" name="batch_number" id="batch_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" value="{{ $purchaseBill->batch_number }}" readonly>
                            </div>
                             <div>
                                <label for="supplier_invoice_no" class="block font-medium text-sm text-gray-700">Supplier Invoice No.</label>
                                <input type="text" name="supplier_invoice_no" id="supplier_invoice_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('supplier_invoice_no', $purchaseBill->supplier_invoice_no) }}">
                            </div>
                            <div>
                                <label for="purchase_date" class="block font-medium text-sm text-gray-700">Purchase Date</label>
                                <input type="date" name="purchase_date" id="purchase_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('purchase_date', $purchaseBill->purchase_date->format('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <label for="supplier_id" class="block font-medium text-sm text-gray-700">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchaseBill->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Medicine Search --}}
                        <div class="mb-6">
                            <label for="medicine-search" class="block font-medium text-sm text-gray-700">Search and Add Medicine</label>
                            <select id="medicine-search" class="mt-1 block w-full"></select>
                        </div>

                        {{-- Medicine Items Table --}}
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full bg-white text-sm">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="text-left py-2 px-2">Medicine</th>
                                        <th class="text-center py-2 px-2 w-32">Batch No</th>
                                        <th class="text-center py-2 px-2 w-24">Qty</th>
                                        <th class="text-center py-2 px-2 w-28">Expiry</th>
                                        <th class="text-center py-2 px-2 w-24">Price</th>
                                        <th class="text-center py-2 px-2 w-24">Disc %</th>
                                        <th class="text-center py-2 px-2 w-24">MRP</th>
                                        <th class="text-center py-2 px-2 w-24">PTR</th>
                                        <th class="text-right py-2 px-2 w-28">Subtotal</th>
                                        <th class="text-center py-2 px-2 w-16">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="medicine-items">
                                    @foreach ($purchaseBill->medicines as $key => $medicine)
                                        @php
                                            // Find the corresponding MedicineBatch to get expiry, mrp, etc.
                                            // This is for display and pre-filling the form accurately.
                                            $medicineBatch = \App\Models\MedicineBatch::where('medicine_id', $medicine->id)
                                                                                    ->where('batch_no', $medicine->pivot->batch_no)
                                                                                    ->first();
                                        @endphp
                                        <tr class="border-b medicine-row">
                                            <td class="py-2 px-2">{{ $medicine->name }} ({{$medicine->mfg_company_name}})<input type="hidden" name="medicines[{{ $key }}][id]" value="{{ $medicine->id }}"><input type="hidden" class="gst-percent" value="{{ $medicine->gst }}"></td>
                                            <td><input type="text" name="medicines[{{ $key }}][batch_no]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="{{ $medicine->pivot->batch_no }}" required></td>
                                            <td><input type="number" name="medicines[{{ $key }}][quantity]" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="{{ $medicine->pivot->quantity }}" min="1" required></td>
                                            <td><input type="date" name="medicines[{{ $key }}][expiry_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ optional($medicineBatch)->expiry_date->format('Y-m-d') }}" required></td>
                                            <td><input type="number" step="0.01" name="medicines[{{ $key }}][price]" class="price-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="{{ $medicine->pivot->price }}" min="0" required></td>
                                            <td><input type="number" step="0.01" name="medicines[{{ $key }}][discount_percentage]" class="discount-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="{{ $medicine->pivot->discount_percentage }}" min="0" max="100"></td>
                                            <td><input type="number" step="0.01" name="medicines[{{ $key }}][mrp]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="{{ optional($medicineBatch)->mrp }}" min="0" required></td>
                                            <td><input type="number" step="0.01" name="medicines[{{ $key }}][ptr]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="{{ optional($medicineBatch)->ptr }}" min="0"></td>
                                            <td class="subtotal-display text-right py-2 px-2">0.00</td>
                                            <td class="text-center"><button type="button" class="remove-item text-red-500 hover:text-red-700 font-bold">X</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" class="text-right font-bold py-2 px-3">Grand Total:</td>
                                        <td id="grand-total" class="text-right font-bold py-2 px-3">0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        {{-- Notes and Discount --}}
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700">Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $purchaseBill->notes) }}</textarea>
                            </div>
                             <div>
                                <label for="cash_discount_percentage" class="block font-medium text-sm text-gray-700">Overall Cash Discount (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="cash_discount_percentage" id="cash_discount_percentage" value="{{ old('cash_discount_percentage', $purchaseBill->cash_discount_percentage) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('purchase_bills.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3">Update Purchase Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#medicine-search').select2({
                placeholder: 'Type to search for a medicine...',
                ajax: {
                    url: '{{ route("purchase_bills.search_medicines") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });

            let itemIndex = {{ $purchaseBill->medicines->count() }};

            // Handle adding a medicine
            $('#medicine-search').on('select2:select', function (e) {
                var data = e.params.data;
                addMedicineRow(data.id, data.text, data.gst);
                $(this).val(null).trigger('change');
            });

            // Handle removing a medicine
            $('#medicine-items').on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });

            // Handle input changes for calculation
            $('#medicine-items').on('input', 'input', updateTotals);
            $('#cash_discount_percentage').on('input', updateTotals);

            function addMedicineRow(id, name, gst) {
                itemIndex++;
                var row = `
                    <tr class="border-b medicine-row">
                        <td class="py-2 px-2">${name}<input type="hidden" name="medicines[${itemIndex}][id]" value="${id}"><input type="hidden" class="gst-percent" value="${gst}"></td>
                        <td><input type="text" name="medicines[${itemIndex}][batch_no]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" required></td>
                        <td><input type="number" name="medicines[${itemIndex}][quantity]" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="1" min="1" required></td>
                        <td><input type="date" name="medicines[${itemIndex}][expiry_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></td>
                        <td><input type="number" step="0.01" name="medicines[${itemIndex}][price]" class="price-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="0.00" min="0" required></td>
                        <td><input type="number" step="0.01" name="medicines[${itemIndex}][discount_percentage]" class="discount-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="0" min="0" max="100"></td>
                        <td><input type="number" step="0.01" name="medicines[${itemIndex}][mrp]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="0.00" min="0" required></td>
                        <td><input type="number" step="0.01" name="medicines[${itemIndex}][ptr]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="0.00" min="0"></td>
                        <td class="subtotal-display text-right py-2 px-2">0.00</td>
                        <td class="text-center"><button type="button" class="remove-item text-red-500 hover:text-red-700 font-bold">X</button></td>
                    </tr>
                `;
                $('#medicine-items').append(row);
            }

            function updateTotals() {
                let grandTotal = 0;
                $('.medicine-row').each(function() {
                    let row = $(this);
                    let quantity = parseFloat(row.find('.quantity-input').val()) || 0;
                    let price = parseFloat(row.find('.price-input').val()) || 0;
                    let discount = parseFloat(row.find('.discount-input').val()) || 0;
                    let gst = parseFloat(row.find('.gst-percent').val()) || 0;

                    let subtotalBeforeDiscount = quantity * price;
                    let discountAmount = subtotalBeforeDiscount * (discount / 100);
                    let subtotalAfterDiscount = subtotalBeforeDiscount - discountAmount;
                    let gstAmount = subtotalAfterDiscount * (gst / 100);
                    let lineTotal = subtotalAfterDiscount + gstAmount;

                    row.find('.subtotal-display').text(lineTotal.toFixed(2));
                    grandTotal += lineTotal;
                });
                
                let cashDiscountPercent = parseFloat($('#cash_discount_percentage').val()) || 0;
                let cashDiscountAmount = grandTotal * (cashDiscountPercent / 100);
                let finalTotal = grandTotal - cashDiscountAmount;

                $('#grand-total').text(finalTotal.toFixed(2));
            }
            
            // Calculate totals for existing items when the page loads
            updateTotals();
        });
    </script>
    @endpush
</x-app-layout>