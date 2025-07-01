{{-- This div represents a single bill item row --}}
<div class="row bill-item-row mb-3 border p-3 rounded bg-light">

    {{-- Medicine Selection --}}
    <div class="col-md-5 mb-2">
        <label for="medicine_id_{{ $index }}" class="form-label">Medicine</label>
        {{--
            This is the Select2 dropdown.
            - `medicine-select` and `select2-basic` classes are used by your JavaScript
              to initialize Select2 on this element.
            - The `name` attribute is crucial for Laravel to process the array of items.
        --}}
        <select class="form-select medicine-select select2-basic" id="medicine_id_{{ $index }}" name="items[{{ $index }}][medicine_id]" required>
            <option value="">Search for Medicine</option> {{-- Default placeholder option --}}

            {{--
                This Blade block handles pre-selecting and displaying the medicine name
                when the form is reloaded (e.g., due to validation errors, using `old()` data).
                It fetches the medicine details (name and unit) from the database
                to display the correct text for Select2 immediately upon load.
            --}}
            @if (isset($oldItem['medicine_id']))
                @php
                    // Ensure you have `use App\Models\Medicine;` at the top of your BillController
                    // or adjust the namespace if your Medicine model is located elsewhere (e.g., `\App\Medicine::find()`).
                    $selectedMedicine = App\Models\Medicine::find($oldItem['medicine_id']);
                    $selectedMedicineText = '';
                    if ($selectedMedicine) {
                        // Concatenate name and unit as expected by Select2's 'text' property
                        $selectedMedicineText = $selectedMedicine->name . ' (' . $selectedMedicine->unit . ')';
                    } else {
                        // Fallback text if medicine not found (shouldn't happen with valid old data)
                        $selectedMedicineText = 'Medicine Not Found (ID: ' . $oldItem['medicine_id'] . ')';
                    }
                @endphp
                {{-- This option provides the full text for Select2 to display for old values --}}
                <option value="{{ $oldItem['medicine_id'] }}" selected>{{ $selectedMedicineText }}</option>
            @endif
        </select>
        {{-- This small text displays the available stock quantity fetched via AJAX --}}
        <small class="text-muted available-quantity-display d-block mt-1">Available: 0</small>
    </div>

    {{-- Quantity Input --}}
    <div class="col-md-2 mb-2">
        <label for="quantity_{{ $index }}" class="form-label">Quantity</label>
        <input type="number" class="form-control item-quantity-input"
               id="quantity_{{ $index }}"
               name="items[{{ $index }}][quantity]"
               value="{{ old('items.' . $index . '.quantity', 1) }}"
               min="1"
               required
               data-available-quantity="0"> {{-- Data attribute to store available stock for JS validation --}}
        {{-- Client-side validation message for insufficient stock --}}
        <div class="text-danger quantity-error-message" style="display:none;">Insufficient stock.</div>
    </div>

    {{-- Unit Price Display (and Hidden Input) --}}
    <div class="col-md-1 mb-2 text-center">
        <label class="form-label">Price</label>
        {{-- Displays the unit price, which is fetched via AJAX or defaults from old data --}}
        <span class="form-control-plaintext unit-price-display" data-unit-price="{{ old('items.' . $index . '.unit_price', 0) }}">
            ₹{{ number_format(old('items.' . $index . '.unit_price', 0), 2) }}
        </span>
        {{-- Hidden field to send the actual unit price to the controller --}}
        <input type="hidden" name="items[{{ $index }}][unit_price]" value="{{ old('items.' . $index . '.unit_price', 0) }}">
    </div>

    {{-- GST Percentage Display (and Hidden Input) --}}
    <div class="col-md-1 mb-2 text-center">
        <label class="form-label">GST %</label>
        {{-- Displays the GST rate, fetched via AJAX or defaults from old data --}}
        <span class="form-control-plaintext gst-rate-display" data-gst-rate="{{ old('items.' . $index . '.gst_rate_percentage', 0) }}">
            {{ number_format(old('items.' . $index . '.gst_rate_percentage', 0), 2) }}%
        </span>
        {{-- Hidden fields to send GST rate and calculated amount to the controller --}}
        <input type="hidden" name="items[{{ $index }}][gst_rate_percentage]" value="{{ old('items.' . $index . '.gst_rate_percentage', 0) }}">
        <input type="hidden" name="items[{{ $index }}][item_gst_amount]" value="{{ old('items.' . $index . '.item_gst_amount', 0) }}">
    </div>

    {{-- Total Amount Display (and Hidden Input) --}}
    <div class="col-md-2 mb-2 text-center">
        <label class="form-label">Total</label>
        {{-- Displays the total amount after tax for this item --}}
        <span class="form-control-plaintext display-total-amount-after-tax">
            ₹{{ number_format(old('items.' . $index . '.total_amount_after_tax', 0), 2) }}
        </span>
        {{-- Hidden fields to send sub_total and total_amount_after_tax to the controller --}}
        <input type="hidden" name="items[{{ $index }}][sub_total]" value="{{ old('items.' . $index . '.sub_total', 0) }}">
        <input type="hidden" name="items[{{ $index }}][total_amount_after_tax]" value="{{ old('items.' . $index . '.total_amount_after_tax', 0) }}">
    </div>

    {{-- Remove Item Button --}}
    <div class="col-md-1 mb-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger remove-item-row" title="Remove Item">
            <i class="bi bi-trash"></i> {{-- Bootstrap Icons for a trash icon --}}
        </button>
    </div>
</div>