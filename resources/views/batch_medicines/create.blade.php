@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <h2 class="mb-4 text-center text-teal fw-bold">Add Medicines to Batch: {{ $batch->batch_number }}</h2>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('batches.medicines.store', $batch->id) }}" method="POST">
                        @csrf

                        <div class="table-responsive mb-4">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="bg-teal text-white rounded-top-4">
                                    <tr>
                                        <th scope="col" class="py-3 ps-4 rounded-top-start-4">S.No.</th>
                                        <th scope="col" class="py-3">Medicine</th>
                                        <th scope="col" class="py-3">Qty</th>
                                        <th scope="col" class="py-3">Pur. Price</th>
                                        <th scope="col" class="py-3">PTR</th>
                                        <th scope="col" class="py-3">GST (%)</th>
                                        <th scope="col" class="py-3">Expiry Date</th>
                                        <th scope="col" class="py-3 text-center rounded-top-end-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="medicine-entries-container">
                                    {{-- Initial medicine entry row --}}
                                    <tr class="medicine-entry-row border-bottom">
                                        <td class="ps-4 py-3 text-center entry-index">1.</td>
                                        <td>
                                            {{-- Add 'medicine-select' class here --}}
                                            <select name="medicines[0][medicine_id]" id="medicine_id_0" class="form-select medicine-select" required>
                                                <option value="">Select Medicine</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" {{ old('medicines.0.medicine_id') == $medicine->id ? 'selected' : '' }}>
                                                        {{ $medicine->name }} ({{ $medicine->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="medicines[0][quantity]" id="quantity_0" class="form-control" value="{{ old('medicines.0.quantity') }}" placeholder="Quantity" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="medicines[0][price]" id="price_0" class="form-control" value="{{ old('medicines.0.price') }}" placeholder="Purchase Price" min="0" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="medicines[0][ptr]" id="ptr_0" class="form-control" value="{{ old('medicines.0.ptr') }}" placeholder="PTR" min="0" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="medicines[0][gst_percent]" id="gst_percent_0" class="form-control" value="{{ old('medicines.0.gst_percent') }}" placeholder="GST %" min="0" max="100" required>
                                        </td>
                                        <td>
                                            <input type="date" name="medicines[0][expiry_date]" id="expiry_date_0" class="form-control" value="{{ old('medicines.0.expiry_date') }}" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-medicine-entry" style="display:none;">Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2 mb-4">
                            <button type="button" id="add-medicine-entry" class="btn btn-outline-teal-primary btn-lg rounded-pill">
                                <i class="bi bi-plus-circle me-2"></i> Add Another Medicine
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal-primary btn-lg rounded-pill px-5 shadow">
                                <i class="bi bi-save me-2"></i> Save Medicines
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts') {{-- Start of page-specific scripts block --}}
<script>
$(document).ready(function() {
    const container = $('#medicine-entries-container');
    const addButton = $('#add-medicine-entry');
    let entryIndex = container.children('.medicine-entry-row').length;

    // Initialize Select2 on existing/first medicine select
    function initializeSelect2(element) {
        element.select2({
            theme: 'bootstrap-5', // Use the Bootstrap 5 theme for better integration
            placeholder: 'Search for medicine...',
            allowClear: true,
            width: 'style' // Set width to the element's style
        });
    }

    // Initialize Select2 for the first row on page load
    initializeSelect2($('#medicine_id_0'));

    // Template for a new medicine entry row
    const newEntryTemplate = `
        <tr class="medicine-entry-row border-bottom">
            <td class="ps-4 py-3 text-center entry-index"></td>
            <td>
                <select name="medicines[TEMP_INDEX][medicine_id]" id="medicine_id_TEMP" class="form-select medicine-select" required>
                    <option value="">Select Medicine</option>
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}">{{ $medicine->name }} ({{ $medicine->unit }})</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="medicines[TEMP_INDEX][quantity]" id="quantity_TEMP" class="form-control" value="" placeholder="Quantity" min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" name="medicines[TEMP_INDEX][price]" id="price_TEMP" class="form-control" value="" placeholder="Purchase Price" min="0" required>
            </td>
            <td>
                <input type="number" step="0.01" name="medicines[TEMP_INDEX][ptr]" id="ptr_TEMP" class="form-control" value="" placeholder="PTR" min="0" required>
            </td>
            <td>
                <input type="number" step="0.01" name="medicines[TEMP_INDEX][gst_percent]" id="gst_percent_TEMP" class="form-control" value="" placeholder="GST %" min="0" max="100" required>
            </td>
            <td>
                <input type="date" name="medicines[TEMP_INDEX][expiry_date]" id="expiry_date_TEMP" class="form-control" value="" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-medicine-entry">Remove</button>
            </td>
        </tr>
    `;

    function updateRemoveButtons() {
        const removeButtons = container.find('.remove-medicine-entry');
        if (removeButtons.length > 1) {
            removeButtons.show();
        } else {
            removeButtons.hide();
        }
    }

    function updateEntryIndices() {
        container.children('.medicine-entry-row').each(function(i, row) {
            $(row).find('.entry-index').text((i + 1) + '.'); // Update S.No.
            $(row).find('[name^="medicines["]').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace(/medicines\[\d+\]/, `medicines[${i}]`));
                const id = $(this).attr('id');
                if (id) {
                    $(this).attr('id', id.replace(/_\d+|TEMP/, `_${i}`));
                }
            });
        });
    }

    addButton.on('click', function() {
        const newEntryHtml = newEntryTemplate.replace(/TEMP_INDEX/g, entryIndex).replace(/_TEMP/g, '_' + entryIndex);
        const newRow = $(newEntryHtml); // Create jQuery object from HTML string

        container.append(newRow);
        
        // IMPORTANT: Initialize Select2 on the new select element after it's added to the DOM
        initializeSelect2(newRow.find('.medicine-select'));

        entryIndex++;
        updateRemoveButtons();
        updateEntryIndices();
    });

    container.on('click', '.remove-medicine-entry', function() {
        if (container.children('.medicine-entry-row').length > 1) { // Prevent removing the last row
            // Destroy Select2 instance before removing the element to prevent memory leaks
            $(this).closest('.medicine-entry-row').find('.medicine-select').select2('destroy');
            
            $(this).closest('.medicine-entry-row').remove();
            updateEntryIndices();
            updateRemoveButtons();
        } else {
            alert('You cannot remove the last medicine entry. Please add at least one medicine to the batch.');
        }
    });

    updateRemoveButtons(); // Initial call to set visibility of remove buttons
});
</script>
@endpush {{-- End of page-specific scripts block --}}