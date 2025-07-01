<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Bill
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <form action="{{ route('bills.store') }}" method="POST" id="bill-form">
                        @csrf
                        {{-- Customer Search --}}
                        <div class="mb-6">
                            <label for="customer-search" class="block font-medium text-sm text-gray-700">Select Customer</label>
                            <select id="customer-search" name="customer_id" class="mt-1 block w-full" required></select>
                             @error('customer_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                                        <th class="text-center py-2 px-2 w-24">Stock</th>
                                        <th class="text-center py-2 px-2 w-24">Qty</th>
                                        <th class="text-center py-2 px-2 w-28">Price</th>
                                        <th class="text-center py-2 px-2 w-24">Disc %</th>
                                        <th class="text-right py-2 px-2 w-28">Amount</th>
                                        <th class="text-center py-2 px-2 w-16">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="medicine-items">
                                    {{-- Rows will be added here by JavaScript --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right font-bold py-2 px-3">Sub-Total:</td>
                                        <td id="sub-total" class="text-right font-bold py-2 px-3">0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right font-bold py-2 px-3">GST:</td>
                                        <td id="gst-total" class="text-right font-bold py-2 px-3">0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-xl">
                                        <td colspan="5" class="text-right font-bold py-2 px-3">Grand Total:</td>
                                        <td id="grand-total" class="text-right font-bold py-2 px-3">0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                             @error('medicines')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('bills.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3">Create Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Customer Search
            $('#customer-search').select2({
                placeholder: 'Select a customer',
                ajax: {
                    url: '{{ route("search.customers") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return { results: data };
                    },
                    cache: true
                }
            });

            // Medicine Search
            $('#medicine-search').select2({
                placeholder: 'Type to search for a medicine...',
                ajax: {
                    url: '{{ route("search.medicines") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });

            let itemIndex = 0;

            // Handle adding a medicine
            $('#medicine-search').on('select2:select', function (e) {
                var data = e.params.data;
                var stock = parseInt(data.text.match(/Stock: (\d+)/)[1], 10);
                addMedicineRow(data.id, data.text, data.gst, stock);
                $(this).val(null).trigger('change');
            });

            // Handle removing a medicine
            $('#medicine-items').on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });

            // Handle input changes for calculation
            $('#medicine-items').on('input', '.quantity-input, .price-input, .discount-input', updateTotals);

            function addMedicineRow(id, name, gst, stock) {
                itemIndex++;
                if ($(`input[name="medicines[${id}][id]"]`).length > 0) {
                    alert('This medicine is already in the bill.');
                    return;
                }
                var row = `
                    <tr class="border-b medicine-row">
                        <td class="py-2 px-2">${name}<input type="hidden" name="medicines[${id}][id]" value="${id}"><input type="hidden" class="gst-percent" value="${gst}"></td>
                        <td class="text-center available-stock">${stock}</td>
                        <td><input type="number" name="medicines[${id}][quantity]" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="1" min="1" max="${stock}" required></td>
                        <td><input type="number" step="0.01" name="medicines[${id}][price]" class="price-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-right" value="0.00" min="0" required></td>
                        <td><input type="number" step="0.01" name="medicines[${id}][discount]" class="discount-input mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center" value="0" min="0" max="100"></td>
                        <td class="amount-display text-right py-2 px-2">0.00</td>
                        <td class="text-center"><button type="button" class="remove-item text-red-500 hover:text-red-700 font-bold">X</button></td>
                    </tr>
                `;
                $('#medicine-items').append(row);
            }

            function updateTotals() {
                let subTotal = 0;
                let gstTotal = 0;

                $('.medicine-row').each(function() {
                    let row = $(this);
                    let quantity = parseFloat(row.find('.quantity-input').val()) || 0;
                    let stock = parseInt(row.find('.available-stock').text(), 10);
                    
                    if (quantity > stock) {
                        alert('Cannot sell more than available stock: ' + stock);
                        row.find('.quantity-input').val(stock);
                        quantity = stock;
                    }

                    let price = parseFloat(row.find('.price-input').val()) || 0;
                    let discount = parseFloat(row.find('.discount-input').val()) || 0;
                    let gst = parseFloat(row.find('.gst-percent').val()) || 0;

                    let amountBeforeDiscount = quantity * price;
                    let discountAmount = amountBeforeDiscount * (discount / 100);
                    let amountAfterDiscount = amountBeforeDiscount - discountAmount;
                    let lineGstAmount = amountAfterDiscount * (gst / 100);
                    
                    subTotal += amountAfterDiscount;
                    gstTotal += lineGstAmount;

                    row.find('.amount-display').text(amountAfterDiscount.toFixed(2));
                });
                
                let grandTotal = subTotal + gstTotal;

                $('#sub-total').text(subTotal.toFixed(2));
                $('#gst-total').text(gstTotal.toFixed(2));
                $('#grand-total').text(grandTotal.toFixed(2));
            }
        });
    </script>
    @endpush
</x-app-layout>