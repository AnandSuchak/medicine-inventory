@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Generate New Bill</h3>
        </div>
        <div class="card-body">
            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Display Session Errors/Success --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('bills.store') }}" method="POST" id="billForm">
                @csrf

                {{-- Bill Header Details --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer</label>
                        {{-- The select2-customer-basic class is just a semantic helper, not strictly required by Select2 itself --}}
                        <select class="form-select select2-customer-basic" id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            {{--
                                This Blade block is crucial for displaying the pre-selected customer
                                (e.g., after a form validation error).
                                It fetches the customer's details to show the correct text in the Select2 dropdown.
                            --}}
                            @if (old('customer_id'))
                                @php
                                    // Ensure App\Models\Customer is correctly namespaced.
                                    $selectedCustomer = App\Models\Customer::find(old('customer_id'));
                                    $selectedCustomerText = '';
                                    if ($selectedCustomer) {
                                        $selectedCustomerText = $selectedCustomer->name . ' (' . $selectedCustomer->phone . ')';
                                    }
                                @endphp
                                {{-- This option provides the value and text for Select2 to display immediately --}}
                                <option value="{{ old('customer_id') }}" selected>{{ $selectedCustomerText }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bill_date" class="form-label">Bill Date</label>
                        <input type="date" class="form-control" id="bill_date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <h4>Bill Items</h4>
                <div id="bill-items-container">
                    {{-- Render initial bill item rows (either empty or from old data after validation error) --}}
                    @if(old('items'))
                        {{-- Loop through old items if form was reloaded with errors --}}
                        @foreach(old('items') as $index => $oldItem)
                            {{-- Include the item row partial, passing the index and old data --}}
                            @include('bills._bill_item_row', ['index' => $index, 'oldItem' => $oldItem])
                        @endforeach
                    @else
                        {{-- If no old items, start with one empty row --}}
                        @include('bills._bill_item_row', ['index' => 0])
                    @endif
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-outline-primary" id="addItemRow">
                        <i class="bi bi-plus-circle"></i> Add Item
                    </button>
                </div>

                {{-- Bill Totals and Discount --}}
                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">Overall Discount Amount (₹)</label>
                            <input type="number" step="0.01" class="form-control" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0.00) }}" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Total (Before Tax):</label>
                            <span id="display-sub-total" class="form-control-plaintext fs-5">₹0.00</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total GST Amount:</label>
                            <span id="display-total-gst" class="form-control-plaintext fs-5">₹0.00</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Net Amount:</label>
                            <span id="display-net-amount" class="form-control-plaintext fs-4 text-primary">₹0.00</span>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Generate Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- API Routes ---
    // These constants make it easy to manage your API endpoints
    const medicineSearchApiRoute = "{{ route('bills.api.medicine_search') }}";
    const medicineStockInfoApiRoute = "{{ route('bills.api.medicine_stock_info', ['medicineId' => '__MEDICINE_ID__']) }}";
    const customerSearchApiRoute = "{{ route('bills.api.customer_search') }}";

    // --- Global Variables ---
    // itemIndex ensures unique IDs and names for dynamically added rows
    // It starts from the count of old items (if any) or 1 for a fresh form
    let itemIndex = {{ old('items') ? count(old('items')) : 1 }};

    // --- Helper Functions ---

    /**
     * Adds a new bill item row to the container.
     * This function generates the HTML for a new row and attaches event listeners.
     */
    function addItemRow() {
        const container = $('#bill-items-container');
        // HTML string for a new bill item row.
        // IDs and names are dynamically generated using `itemIndex` to ensure uniqueness.
        const newRowHtml = `
            <div class="row bill-item-row mb-3 border p-3 rounded bg-light">
                <div class="col-md-5 mb-2">
                    <label for="medicine_id_${itemIndex}" class="form-label">Medicine</label>
                    <select class="form-select medicine-select select2-basic" id="medicine_id_${itemIndex}" name="items[${itemIndex}][medicine_id]" required>
                        <option value="">Search for Medicine</option>
                    </select>
                    <small class="text-muted available-quantity-display d-block mt-1">Available: 0</small>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="quantity_${itemIndex}" class="form-label">Quantity</label>
                    <input type="number" class="form-control item-quantity-input"
                           id="quantity_${itemIndex}"
                           name="items[${itemIndex}][quantity]"
                           value="1" min="1" required data-available-quantity="0">
                    <div class="text-danger quantity-error-message" style="display:none;">Insufficient stock.</div>
                </div>
                <div class="col-md-1 mb-2 text-center">
                    <label class="form-label">Price</label>
                    <span class="form-control-plaintext unit-price-display" data-unit-price="0">₹0.00</span>
                    <input type="hidden" name="items[${itemIndex}][unit_price]" value="0">
                </div>
                <div class="col-md-1 mb-2 text-center">
                    <label class="form-label">GST %</label>
                    <span class="form-control-plaintext gst-rate-display" data-gst-rate="0">0.00%</span>
                    <input type="hidden" name="items[${itemIndex}][gst_rate_percentage]" value="0">
                    <input type="hidden" name="items[${itemIndex}][item_gst_amount]" value="0">
                </div>
                <div class="col-md-2 mb-2 text-center">
                    <label class="form-label">Total</label>
                    <span class="form-control-plaintext display-total-amount-after-tax">₹0.00</span>
                    <input type="hidden" name="items[${itemIndex}][sub_total]" value="0">
                    <input type="hidden" name="items[${itemIndex}][total_amount_after_tax]" value="0">
                </div>
                <div class="col-md-1 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-item-row" title="Remove Item">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;

        const renderedRow = $(newRowHtml); // Convert HTML string to a jQuery object
        container.append(renderedRow); // Add the new row to the DOM
        attachEventListenersToRow(renderedRow); // Attach all necessary event listeners to the new row
        itemIndex++; // Increment index for the next row
        updateTotals(); // Recalculate overall bill totals
    }

    /**
     * Removes a bill item row from the DOM.
     * @param {HTMLElement} button The remove button element that was clicked.
     */
    function removeItemRow(button) {
        const row = $(button).closest('.bill-item-row');
        if (row.length) {
            // Important: Destroy Select2 instance before removing the element
            // to prevent memory leaks or unexpected behavior.
            const medicineSelect = row.find('.medicine-select');
            if (medicineSelect.data('select2')) {
                medicineSelect.select2('destroy');
            }
            row.remove(); // Remove the row from the DOM
            updateTotals(); // Recalculate overall bill totals
        }
    }

    /**
     * Fetches medicine stock information (available quantity, unit price, GST rate) via AJAX.
     * Updates relevant display fields and hidden inputs within the row.
     * @param {string} medicineId The ID of the selected medicine.
     * @param {jQueryObject} row The jQuery object representing the current bill item row.
     */
    async function fetchMedicineStockInfo(medicineId, row) {
        // Select relevant elements within the current row using jQuery
        const availableQuantityDisplay = row.find('.available-quantity-display');
        const unitPriceDisplay = row.find('.unit-price-display');
        const gstRateDisplay = row.find('.gst-rate-display');
        const unitPriceInput = row.find('input[name$="[unit_price]"]');
        const gstRateInput = row.find('input[name$="[gst_rate_percentage]"]');
        const quantityInput = row.find('.item-quantity-input');

        // Show loading states while fetching data
        availableQuantityDisplay.text('Available: Loading...');
        unitPriceDisplay.text('₹Loading...');
        gstRateDisplay.text('Loading%');
        quantityInput.data('availableQuantity', 0); // Reset available quantity data attribute

        // If no medicine is selected (e.g., cleared selection), reset values and return
        if (!medicineId) {
            availableQuantityDisplay.text('Available: 0');
            unitPriceDisplay.text('₹0.00').data('unitPrice', 0);
            gstRateDisplay.text('0%').data('gstRate', 0);
            quantityInput.data('availableQuantity', 0);
            unitPriceInput.val(0);
            gstRateInput.val(0);
            updateItemCalculations(row); // Recalculate for empty state
            return;
        }

        try {
            // Construct the API URL by replacing the placeholder with the actual medicine ID
            const url = medicineStockInfoApiRoute.replace('__MEDICINE_ID__', medicineId);
            const response = await fetch(url); // Perform the AJAX request

            if (!response.ok) { // Check if the HTTP response was successful
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json(); // Parse the JSON response

            // Safely parse values as floats, defaulting to 0 if they are invalid or missing
            const totalAvailableQuantity = parseFloat(data.total_available_quantity) || 0;
            const unitPrice = parseFloat(data.unit_price) || 0;
            const gstRatePercentage = parseFloat(data.gst_rate_percentage) || 0;

            // Update display elements and data attributes with fetched information
            availableQuantityDisplay.text(`Available: ${totalAvailableQuantity}`);
            unitPriceDisplay.text(`₹${unitPrice.toFixed(2)}`).data('unitPrice', unitPrice);
            gstRateDisplay.text(`${gstRatePercentage.toFixed(2)}%`).data('gstRate', gstRatePercentage);
            quantityInput.data('availableQuantity', totalAvailableQuantity);

            // Update hidden input fields, which will be submitted with the form
            unitPriceInput.val(unitPrice.toFixed(2));
            gstRateInput.val(gstRatePercentage.toFixed(2));

        } catch (error) {
            console.error('Error fetching medicine stock info:', error);
            // Revert to default/error states on fetch failure
            availableQuantityDisplay.text('Available: Error');
            unitPriceDisplay.text('₹0.00').data('unitPrice', 0);
            gstRateDisplay.text('0%').data('gstRate', 0);
            quantityInput.data('availableQuantity', 0);
            unitPriceInput.val(0);
            gstRateInput.val(0);
        } finally {
            updateItemCalculations(row); // Always trigger calculations after stock info is processed
        }
    }

    /**
     * Validates if the entered quantity for a medicine item exceeds its available stock.
     * Displays an error message and applies invalid styling if quantity is insufficient.
     * @param {HTMLElement} quantityInput The quantity input element to validate.
     * @returns {boolean} True if the quantity is valid, false otherwise.
     */
    function validateQuantity(quantityInput) {
        const row = $(quantityInput).closest('.bill-item-row');
        const errorMessage = row.find('.quantity-error-message');

        const enteredQuantity = parseFloat($(quantityInput).val()) || 0;
        const availableQuantity = parseFloat($(quantityInput).data('availableQuantity')) || 0;

        if (enteredQuantity > availableQuantity) {
            errorMessage.show();
            $(quantityInput).addClass('is-invalid'); // Add Bootstrap validation style
            return false;
        } else {
            errorMessage.hide();
            $(quantityInput).removeClass('is-invalid'); // Remove validation style if valid
            return true;
        }
    }

    /**
     * Updates calculations for a single bill item row (subtotal, item-level GST, total after tax).
     * @param {jQueryObject} row The jQuery object representing the current bill item row.
     */
    function updateItemCalculations(row) {
        const quantityInput = row.find('.item-quantity-input');
        const quantity = parseFloat(quantityInput.val()) || 0;
        // Retrieve unit price and GST rate from data attributes (set by fetchMedicineStockInfo)
        const unitPrice = parseFloat(row.find('.unit-price-display').data('unitPrice')) || 0;
        const gstRatePercentage = parseFloat(row.find('.gst-rate-display').data('gstRate')) || 0;

        // Ensure hidden inputs reflect the current calculated values for form submission
        row.find('input[name$="[unit_price]"]').val(unitPrice.toFixed(2));
        row.find('input[name$="[gst_rate_percentage]"]').val(gstRatePercentage.toFixed(2));

        const subTotal = quantity * unitPrice;
        const itemGstAmount = (subTotal * gstRatePercentage) / 100;
        const totalAmountAfterTax = subTotal + itemGstAmount;

        // Update hidden inputs for form submission with calculated totals for the item
        row.find('input[name$="[sub_total]"]').val(subTotal.toFixed(2));
        row.find('input[name$="[item_gst_amount]"]').val(itemGstAmount.toFixed(2));
        row.find('input[name$="[total_amount_after_tax]"]').val(totalAmountAfterTax.toFixed(2));

        // Update display spans for the user to see the item totals
        row.find('.display-total-amount-after-tax').text('₹' + totalAmountAfterTax.toFixed(2));

        validateQuantity(quantityInput); // Re-validate quantity after calculation
        updateTotals(); // Trigger recalculation of overall bill totals
    }

    /**
     * Updates the overall bill totals (sub total, total GST, net amount) and controls
     * the submission button's enabled/disabled state based on validation and item presence.
     */
    function updateTotals() {
        let totalBillSubTotal = 0;
        let totalBillGstAmount = 0;
        let hasInvalidQuantity = false;

        // Iterate over all bill item rows to sum their individual totals
        $('.bill-item-row').each(function() {
            const row = $(this);
            totalBillSubTotal += parseFloat(row.find('input[name$="[sub_total]"]').val()) || 0;
            totalBillGstAmount += parseFloat(row.find('input[name$="[item_gst_amount]"]').val()) || 0;
            // Check if any item has an invalid quantity to disable submission
            if (row.find('.item-quantity-input').hasClass('is-invalid')) {
                hasInvalidQuantity = true;
            }
        });

        const discountAmount = parseFloat($('#discount_amount').val()) || 0;
        const netAmount = (totalBillSubTotal + totalBillGstAmount) - discountAmount;

        // Update overall display elements for the bill totals
        $('#display-sub-total').text('₹' + totalBillSubTotal.toFixed(2));
        $('#display-total-gst').text('₹' + totalBillGstAmount.toFixed(2));
        $('#display-net-amount').text('₹' + netAmount.toFixed(2));

        // Control the submit button's state
        const submitButton = $('button[type="submit"]');
        const hasItems = $('.bill-item-row').length > 0; // Check if there's at least one item
        if (submitButton) {
            // Disable button if any quantity is invalid or no items are added
            submitButton.prop('disabled', hasInvalidQuantity || !hasItems);
        }
    }

    /**
     * Attaches all necessary event listeners (Select2, input changes, remove button)
     * to a given bill item row. This function is called for both initial rows and new rows.
     * @param {jQueryObject} row The jQuery object representing the current bill item row.
     */
    function attachEventListenersToRow(row) {
        const medicineSelect = row.find('.medicine-select');
        const quantityInput = row.find('.item-quantity-input');
        const removeButton = row.find('.remove-item-row');

        // Destroy any existing Select2 instance before re-initializing.
        // This is important if a row is re-rendered or Select2 is applied multiple times.
        if (medicineSelect.data('select2')) {
            medicineSelect.select2('destroy');
        }

        // Initialize Select2 for the medicine selection dropdown within this row
        medicineSelect.select2({
            theme: 'bootstrap-5', // Apply Bootstrap 5 theme for consistent styling
            placeholder: 'Search for medicine...',
            minimumInputLength: 2, // Minimum characters required to trigger search
            ajax: {
                url: medicineSearchApiRoute, // API endpoint for medicine search
                dataType: 'json',
                delay: 250, // Debounce delay to prevent too many requests
                data: function(params) {
                    return { q: params.term }; // `q` is the query parameter the backend expects
                },
                processResults: function(data) {
                    // Your API returns data in the format [{id: ..., text: ...}],
                    // so we just need to wrap it in a `results` object.
                    return { results: data };
                },
                cache: true // Cache results for faster subsequent searches
            }
        });

        // Event listener for when a medicine is selected from the dropdown
        medicineSelect.on('select2:select', async function(e) {
            const medicineId = e.params.data.id; // Get the ID of the selected medicine
            await fetchMedicineStockInfo(medicineId, row); // Fetch and update stock info for this item
        });

        // Handle initial values for medicine dropdowns (e.g., when old() data is loaded).
        // If an initial medicine ID is present, fetch its stock info to populate related fields.
        const initialMedicineId = medicineSelect.val();
        if (initialMedicineId) {
            fetchMedicineStockInfo(initialMedicineId, row);
        }

        // Event listener for changes in the quantity input field
        quantityInput.on('input change', function() {
            updateItemCalculations(row); // Recalculate item totals when quantity changes
        });

        // Event listener for the "Remove Item" button within the row
        removeButton.on('click', function() {
            removeItemRow(this); // Call removeItemRow to remove this specific row
        });
    }

    // --- Main Document Ready Block (Executes once the DOM is fully loaded) ---
    $(document).ready(function() {
        // --- Customer Select2 Initialization (Static Element) ---
        // Initialize Select2 for the main customer dropdown at the top of the form.
        // This is done once on page load as it's not part of dynamic rows.
        $('#customer_id').select2({
            theme: 'bootstrap-5', // Apply Bootstrap 5 theme
            placeholder: 'Search for customer...',
            minimumInputLength: 2, // Minimum characters to trigger search
            ajax: {
                url: customerSearchApiRoute, // API endpoint for customer search
                dataType: 'json',
                delay: 250, // Debounce delay
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            }
        });

        // --- Initializing Existing Bill Item Rows ---
        // Loop through all bill item rows that are present when the page first loads
        // (either the default empty row or rows populated by `old()` data).
        // Attach all relevant event listeners, including Select2, to each.
        $('.bill-item-row').each(function() {
            attachEventListenersToRow($(this));
        });

        // --- Event Listeners for Bill-Level Actions ---
        // Attach click listener to the "Add Item" button
        $('#addItemRow').on('click', addItemRow);
        // Attach input change listener to the overall discount amount field
        $('#discount_amount').on('input', updateTotals);

        // --- Initial Calculations ---
        // Perform initial calculations for the entire bill on page load
        // to ensure all totals are correctly displayed from the start (especially with old data).
        updateTotals();
    });
</script>
@endpush