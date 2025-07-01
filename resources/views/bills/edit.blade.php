@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center text-teal mb-4">Edit Bill - {{ $bill->bill_number }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('bills.update', $bill->id) }}" id="billForm">
        @csrf
        @method('PUT')

        <div class="card shadow-sm p-4">
            <div class="form-group mb-3">
                <label for="customer_id" class="form-label text-teal">Select Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Select a Customer</option>
                    @foreach(App\Models\Customer::all() as $customer)
                        <option value="{{ $customer->id }}" {{ $bill->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <h4 class="mt-4 text-teal">Medicines</h4>

            <div class="row fw-bold text-muted mb-2">
                <div class="col-md-4">Medicine</div>
                <div class="col-md-2">Quantity</div>
                <div class="col-md-2">Price</div>
                <div class="col-md-1 text-center">GST %</div>
                <div class="col-md-2 text-center">Total</div>
                <div class="col-md-1">Action</div>
            </div>

            <div id="medicine-items">
                @foreach($bill->billItems as $i => $item)
                <div class="row mb-2 bill-item-row" data-item-id="{{ $item->id }}">
                    <div class="col-md-4">
                        <select name="items[{{ $i }}][medicine_id]" class="form-control medicine-select" required>
                            <option value="">-- Select Medicine --</option>
                            @foreach(App\Models\Medicine::all() as $medicine)
                                <option value="{{ $medicine->id }}"
                                    {{ $item->medicine_id == $medicine->id ? 'selected' : '' }}>
                                    {{ $medicine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        {{-- Added data-original-quantity for client-side stock validation adjustment --}}
                        <input type="number" name="items[{{ $i }}][quantity]" class="form-control quantity item-quantity-input" min="1" value="{{ $item->quantity }}" required data-available-quantity="0" data-original-quantity="{{ $item->quantity }}">
                        <div class="text-danger quantity-error-message" style="display:none;">Insufficient stock.</div>
                    </div>
                    <div class="col-md-2">
                        <span class="form-control-plaintext unit-price-display" data-unit-price="{{ number_format($item->unit_price, 2, '.', '') }}">₹{{ number_format($item->unit_price, 2) }}</span>
                        <input type="hidden" name="items[{{ $i }}][unit_price]" value="{{ number_format($item->unit_price, 2, '.', '') }}">
                    </div>
                    <div class="col-md-1 text-center">
                        <span class="form-control-plaintext gst-rate-display" data-gst-rate="{{ number_format($item->gst_rate_percentage, 2, '.', '') }}">{{ number_format($item->gst_rate_percentage, 2) }}%</span>
                        <input type="hidden" name="items[{{ $i }}][gst_rate_percentage]" value="{{ number_format($item->gst_rate_percentage, 2, '.', '') }}">
                        <input type="hidden" name="items[{{ $i }}][item_gst_amount]" value="{{ number_format($item->item_gst_amount, 2, '.', '') }}">
                    </div>
                    <div class="col-md-2">
                        <span class="form-control-plaintext display-total-amount-after-tax">₹{{ number_format($item->total_amount_after_tax, 2) }}</span>
                        <input type="hidden" name="items[{{ $i }}][sub_total]" value="{{ number_format($item->sub_total, 2, '.', '') }}">
                        <input type="hidden" name="items[{{ $i }}][total_amount_after_tax]" value="{{ number_format($item->total_amount_after_tax, 2, '.', '') }}">
                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                        <input type="hidden" name="items[{{ $i }}][batch_id]" value="{{ $item->batch_id }}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-item-row" data-item-id="{{ $item->id }}">X</button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-item">+ Add Medicine</button>

            <div class="mb-3">
                <label for="notes" class="form-label text-teal">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $bill->notes) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="discount_amount" class="form-label text-teal">Overall Discount Amount (₹)</label>
                <input type="number" step="0.01" class="form-control" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', $bill->discount_amount) }}" min="0">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label text-teal">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ $bill->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $bill->status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ $bill->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 offset-md-6">
                    <div class="mb-3">
                        <label class="form-label">Sub Total (Before Tax):</label>
                        <span id="display-sub-total" class="form-control-plaintext fs-5">₹{{ number_format($bill->sub_total_before_tax, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total GST Amount:</label>
                        <span id="display-total-gst" class="form-control-plaintext fs-5">₹{{ number_format($bill->total_gst_amount, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Net Amount:</label>
                        <span id="display-net-amount" class="form-control-plaintext fs-4 text-primary">₹{{ number_format($bill->net_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('bills.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-teal">Update Bill</button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script>
    // --- API Routes ---
    const medicineSearchApiRoute = "{{ route('bills.api.medicine_search') }}";
    const medicineStockInfoApiRoute = "{{ route('bills.api.medicine_stock_info', ['medicineId' => '__MEDICINE_ID__']) }}";
    const customerSearchApiRoute = "{{ route('bills.api.customer_search') }}";

    // --- Global Variables ---
    let itemIndex = {{ $bill->billItems->count() > 0 ? $bill->billItems->count() : 0 }};
    let deletedItemIds = [];

    // --- Helper Functions ---

    /**
     * Adds a new bill item row to the container.
     */
    function addItemRow() {
        const container = $('#medicine-items');
        const newRowHtml = `
            <div class="row mb-2 bill-item-row">
                <div class="col-md-4">
                    <select name="items[${itemIndex}][medicine_id]" class="form-control medicine-select" required>
                        <option value="">-- Select Medicine --</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity item-quantity-input" min="1" value="1" required data-available-quantity="0" data-original-quantity="0">
                    <div class="text-danger quantity-error-message" style="display:none;">Insufficient stock.</div>
                </div>
                <div class="col-md-2">
                    <span class="form-control-plaintext unit-price-display" data-unit-price="0">₹0.00</span>
                    <input type="hidden" name="items[${itemIndex}][unit_price]" value="0">
                    <input type="hidden" name="items[${itemIndex}][batch_id]" value="">
                </div>
                <div class="col-md-1 text-center">
                    <span class="form-control-plaintext gst-rate-display" data-gst-rate="0">0.00%</span>
                    <input type="hidden" name="items[${itemIndex}][gst_rate_percentage]" value="0">
                    <input type="hidden" name="items[${itemIndex}][item_gst_amount]" value="0">
                </div>
                <div class="col-md-2">
                    <span class="form-control-plaintext display-total-amount-after-tax">₹0.00</span>
                    <input type="hidden" name="items[${itemIndex}][sub_total]" value="0">
                    <input type="hidden" name="items[${itemIndex}][total_amount_after_tax]" value="0">
                    <input type="hidden" name="items[${itemIndex}][id]" value="">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-item-row">X</button>
                </div>
            </div>
        `;

        const renderedRow = $(newRowHtml);
        container.append(renderedRow);
        attachEventListenersToRow(renderedRow);
        itemIndex++;
        updateTotals();
    }

    /**
     * Removes a bill item row from the DOM and marks its ID for deletion if it's an existing item.
     * @param {HTMLElement} button The remove button element that was clicked.
     */
    function removeItemRow(button) {
        const row = $(button).closest('.bill-item-row');
        const itemId = row.data('item-id');

        if (itemId) {
            deletedItemIds.push(itemId);
        }

        if (row.length) {
            const medicineSelect = row.find('.medicine-select');
            if (medicineSelect.data('select2')) {
                medicineSelect.select2('destroy');
            }
            row.remove();
            updateTotals();
        }
    }

    /**
     * Fetches medicine stock information (available quantity, unit price, GST rate) via AJAX.
     * Updates relevant display fields and hidden inputs within the row.
     * @param {string} medicineId The ID of the selected medicine.
     * @param {jQueryObject} row The jQuery object representing the current bill item row.
     */
    async function fetchMedicineStockInfo(medicineId, row) {
        const availableQuantityDisplay = row.find('.available-quantity-display');
        const unitPriceDisplay = row.find('.unit-price-display');
        const gstRateDisplay = row.find('.gst-rate-display');
        const unitPriceInput = row.find('input[name$="[unit_price]"]');
        const gstRateInput = row.find('input[name$="[gst_rate_percentage]"]');
        const quantityInput = row.find('.item-quantity-input');
        const batchIdInput = row.find('input[name$="[batch_id]"]');

        availableQuantityDisplay.text('Available: Loading...');
        unitPriceDisplay.text('₹Loading...');
        gstRateDisplay.text('Loading%');
        quantityInput.data('availableQuantity', 0);

        if (!medicineId) {
            availableQuantityDisplay.text('Available: 0');
            unitPriceDisplay.text('₹0.00').data('unitPrice', 0);
            gstRateDisplay.text('0%').data('gstRate', 0);
            quantityInput.data('availableQuantity', 0);
            unitPriceInput.val(0);
            gstRateInput.val(0);
            batchIdInput.val('');
            updateItemCalculations(row);
            return;
        }

        try {
            const url = medicineStockInfoApiRoute.replace('__MEDICINE_ID__', medicineId);
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();

            const totalAvailableQuantity = parseFloat(data.total_available_quantity) || 0;
            const unitPrice = parseFloat(data.unit_price) || 0;
            const gstRatePercentage = parseFloat(data.gst_rate_percentage) || 0;
            const batchId = data.batch_id || ''; // Assuming API returns batch_id

            availableQuantityDisplay.text(`Available: ${totalAvailableQuantity}`);
            unitPriceDisplay.text(`₹${unitPrice.toFixed(2)}`).data('unitPrice', unitPrice);
            gstRateDisplay.text(`${gstRatePercentage.toFixed(2)}%`).data('gstRate', gstRatePercentage);
            // Store the *actual current* available quantity from the API response
            quantityInput.data('availableQuantity', totalAvailableQuantity);

            unitPriceInput.val(unitPrice.toFixed(2));
            gstRateInput.val(gstRatePercentage.toFixed(2));
            batchIdInput.val(batchId);

        } catch (error) {
            console.error('Error fetching medicine stock info:', error);
            availableQuantityDisplay.text('Available: Error');
            unitPriceDisplay.text('₹0.00').data('unitPrice', 0);
            gstRateDisplay.text('0%').data('gstRate', 0);
            quantityInput.data('availableQuantity', 0);
            unitPriceInput.val(0);
            gstRateInput.val(0);
            batchIdInput.val('');
        } finally {
            updateItemCalculations(row); // This will trigger validateQuantity
        }
    }

    /**
     * Validates if the entered quantity for a medicine item exceeds its effective available stock.
     */
    function validateQuantity(quantityInput) {
        const row = $(quantityInput).closest('.bill-item-row');
        const errorMessage = row.find('.quantity-error-message');
        const itemId = row.data('item-id'); // Get the item ID if it's an existing item

        const enteredQuantity = parseFloat($(quantityInput).val()) || 0;
        let availableQuantity = parseFloat($(quantityInput).data('availableQuantity')) || 0; // Current stock from API
        const originalQuantity = parseFloat($(quantityInput).data('originalQuantity')) || 0; // Quantity originally on this bill item

        // If this is an existing bill item, add its original quantity back for validation purposes
        if (itemId && itemId !== '') { // Check if itemId exists and is not empty (for new rows)
            availableQuantity += originalQuantity;
        }

        // Now, compare the entered quantity against this adjusted available quantity
        if (enteredQuantity > availableQuantity) {
            errorMessage.show();
            $(quantityInput).addClass('is-invalid');
            return false;
        } else {
            errorMessage.hide();
            $(quantityInput).removeClass('is-invalid');
            return true;
        }
    }

    /**
     * Updates calculations for a single bill item row.
     */
    function updateItemCalculations(row) {
        const quantityInput = row.find('.item-quantity-input');
        const quantity = parseFloat(quantityInput.val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price-display').data('unitPrice')) || 0;
        const gstRatePercentage = parseFloat(row.find('.gst-rate-display').data('gstRate')) || 0;

        row.find('input[name$="[unit_price]"]').val(unitPrice.toFixed(2));
        row.find('input[name$="[gst_rate_percentage]"]').val(gstRatePercentage.toFixed(2));

        const subTotal = quantity * unitPrice;
        const itemGstAmount = (subTotal * gstRatePercentage) / 100;
        const totalAmountAfterTax = subTotal + itemGstAmount;

        row.find('input[name$="[sub_total]"]').val(subTotal.toFixed(2));
        row.find('input[name$="[item_gst_amount]"]').val(itemGstAmount.toFixed(2));
        row.find('input[name$="[total_amount_after_tax]"]').val(totalAmountAfterTax.toFixed(2));

        row.find('.display-total-amount-after-tax').text('₹' + totalAmountAfterTax.toFixed(2));

        validateQuantity(quantityInput);
        updateTotals();
    }

    /**
     * Updates the overall bill totals and controls the submission button's state.
     */
    function updateTotals() {
        let totalBillSubTotal = 0;
        let totalBillGstAmount = 0;
        let hasInvalidQuantity = false;

        $('.bill-item-row').each(function() {
            const row = $(this);
            totalBillSubTotal += parseFloat(row.find('input[name$="[sub_total]"]').val()) || 0;
            totalBillGstAmount += parseFloat(row.find('input[name$="[item_gst_amount]"]').val()) || 0;
            if (row.find('.item-quantity-input').hasClass('is-invalid')) {
                hasInvalidQuantity = true;
            }
        });

        const discountAmount = parseFloat($('#discount_amount').val()) || 0;
        const netAmount = (totalBillSubTotal + totalBillGstAmount) - discountAmount;

        $('#display-sub-total').text('₹' + totalBillSubTotal.toFixed(2));
        $('#display-total-gst').text('₹' + totalBillGstAmount.toFixed(2));
        $('#display-net-amount').text('₹' + netAmount.toFixed(2));

        const submitButton = $('button[type="submit"]');
        const hasItems = $('.bill-item-row').length > 0;
        if (submitButton) {
            submitButton.prop('disabled', hasInvalidQuantity || !hasItems);
        }
    }

    /**
     * Attaches all necessary event listeners to a given bill item row.
     */
    function attachEventListenersToRow(row) {
        const medicineSelect = row.find('.medicine-select');
        const quantityInput = row.find('.item-quantity-input');
        const removeButton = row.find('.remove-item-row');
        const itemId = row.data('item-id'); // Get item ID for conditional logic

        if (medicineSelect.data('select2')) {
            medicineSelect.select2('destroy');
        }

        medicineSelect.select2({
            theme: 'bootstrap-5',
            placeholder: 'Search for medicine...',
            minimumInputLength: 2,
            ajax: {
                url: medicineSearchApiRoute,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            }
        });

        medicineSelect.on('select2:select', async function(e) {
            const medicineId = e.params.data.id;
            // When medicine is changed for an *existing* item, its original quantity becomes 0 for validation purposes
            if (itemId && itemId !== '') {
                 quantityInput.data('originalQuantity', 0);
            }
            await fetchMedicineStockInfo(medicineId, row);
        });

        // For existing items, trigger fetch to populate price/stock info
        // only if medicine_id is already set (i.e., not a newly added empty row)
        const initialMedicineId = medicineSelect.val();
        if (initialMedicineId) {
            fetchMedicineStockInfo(initialMedicineId, row);
        }

        quantityInput.on('input change', function() {
            updateItemCalculations(row);
        });

        removeButton.on('click', function() {
            removeItemRow(this);
        });
    }

    // --- Main Document Ready Block ---
    $(document).ready(function() {
        // --- Customer Select2 Initialization ---
        $('#customer_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search for customer...',
            minimumInputLength: 2,
            ajax: {
                url: customerSearchApiRoute,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            }
        });

        const initialCustomerId = $('#customer_id').val();
        if (initialCustomerId) {
            const customerId = initialCustomerId;
            const customerText = $('#customer_id option:selected').text();
            if (customerId && customerText) {
                $('#customer_id').append(new Option(customerText, customerId, true, true)).trigger('change');
            }
        }

        // Attach event listeners to pre-loaded bill item rows
        $('.bill-item-row').each(function() {
            attachEventListenersToRow($(this));
        });

        // Attach click listener to the "Add Item" button
        $('#add-item').on('click', addItemRow);
        // Attach input change listener to the overall discount amount field
        $('#discount_amount').on('input', updateTotals);

        // Add a hidden input to store deleted_item_ids before form submission
        $('#billForm').on('submit', function() {
            $('#billForm input[name="deleted_item_ids[]"]').remove();

            deletedItemIds.forEach(function(id) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'deleted_item_ids[]',
                    value: id
                }).appendTo('#billForm');
            });
        });

        // Initial calculations
        updateTotals();
    });
</script>

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
</style>
@endpush
