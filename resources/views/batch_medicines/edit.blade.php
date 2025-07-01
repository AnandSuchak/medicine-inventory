@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        {{-- Wider column for the table --}}
        <div class="col-12 col-xl-11">
            <h2 class="mb-4 text-center text-teal fw-bold">Edit Medicines for Batch: {{ $batch->batch_number }}</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
                    <form action="{{ route('batches.medicines.update', $batch->id) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                    @forelse($batch->medicines as $index => $medicine)
                                        <tr class="medicine-entry-row border-bottom" id="medicine_{{ $medicine->id }}">
                                            <td class="ps-4 py-3 text-center entry-index">{{ $index + 1 }}.</td>
                                            <td>
                                                {{-- Hidden input for medicine_id, and display name as readonly --}}
                                                <input type="hidden" name="medicines[{{ $index }}][medicine_id]" value="{{ $medicine->id }}">
                                                <input type="text" id="medicine_name_{{ $index }}" class="form-control" value="{{ $medicine->name }} ({{ $medicine->unit }})" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="medicines[{{ $index }}][quantity]" id="quantity_{{ $index }}" class="form-control" value="{{ old('medicines.' . $index . '.quantity', $medicine->pivot->quantity) }}" placeholder="Quantity" min="0" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="medicines[{{ $index }}][price]" id="price_{{ $index }}" class="form-control" value="{{ old('medicines.' . $index . '.price', $medicine->pivot->price) }}" placeholder="Purchase Price" min="0" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="medicines[{{ $index }}][ptr]" id="ptr_{{ $index }}" class="form-control" value="{{ old('medicines.' . $index . '.ptr', $medicine->pivot->ptr) }}" placeholder="PTR" min="0" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="medicines[{{ $index }}][gst_percent]" id="gst_percent_{{ $index }}" class="form-control" value="{{ old('medicines.' . $index . '.gst_percent', $medicine->pivot->gst_percent) }}" placeholder="GST %" min="0" max="100" required>
                                            </td>
                                            <td>
                                                <input type="date" name="medicines[{{ $index }}][expiry_date]" id="expiry_date_{{ $index }}" class="form-control" value="{{ old('medicines.' . $index . '.expiry_date', \Carbon\Carbon::parse($medicine->pivot->expiry_date)->format('Y-m-d')) }}" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-medicine-entry"
                                                    data-medicine-id="{{ $medicine->id }}" data-batch-id="{{ $batch->id }}"></button>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- If no medicines exist, show one blank entry for adding new ones --}}
                                        <tr class="medicine-entry-row border-bottom">
                                            <td class="ps-4 py-3 text-center entry-index">1.</td>
                                            <td>
                                                <select name="medicines[0][medicine_id]" id="medicine_id_0" class="form-select" required>
                                                    <option value="">Select Medicine</option>
                                                    @foreach($allMedicines as $med)
                                                        <option value="{{ $med->id }}" {{ old('medicines.0.medicine_id') == $med->id ? 'selected' : '' }}>
                                                            {{ $med->name }} ({{ $med->unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="medicines[0][quantity]" id="quantity_0" class="form-control" value="{{ old('medicines.0.quantity') }}" placeholder="Quantity" min="0" required>
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
                                    @endforelse
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
                                <i class="bi bi-arrow-clockwise me-2"></i> Update Medicines
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('medicine-entries-container');
    const addButton = document.getElementById('add-medicine-entry');
    // Correctly initialize entryIndex based on current number of rendered entries
    let entryIndex = container.querySelectorAll('.medicine-entry-row').length;

    // Template for a new medicine entry row
    const newEntryTemplate = `
        <tr class="medicine-entry-row border-bottom">
            <td class="ps-4 py-3 text-center entry-index"></td>
            <td>
                <select name="medicines[TEMP_INDEX][medicine_id]" id="medicine_id_TEMP" class="form-select" required>
                    <option value="">Select Medicine</option>
                    @foreach($allMedicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->unit }})</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="medicines[TEMP_INDEX][quantity]" id="quantity_TEMP" class="form-control" value="" placeholder="Quantity" min="0" required>
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

    function updateRemoveButtonsVisibility() {
        const removeButtons = container.querySelectorAll('.remove-medicine-entry');
        if (removeButtons.length > 1) {
            removeButtons.forEach(button => button.style.display = 'block');
        } else {
            removeButtons.forEach(button => button.style.display = 'none');
        }
    }

    function reindexEntries() {
        container.querySelectorAll('.medicine-entry-row').forEach((row, i) => {
            row.querySelector('.entry-index').textContent = (i + 1) + '.'; // Update S.No.
            row.querySelectorAll('[name^="medicines["]').forEach(input => {
                const currentName = input.getAttribute('name');
                const newName = currentName.replace(/medicines\[\d+\]/, `medicines[${i}]`);
                input.setAttribute('name', newName);

                const currentId = input.getAttribute('id');
                if (currentId) {
                    const newId = currentId.replace(/_(\d+|TEMP)/, `_${i}`);
                    input.setAttribute('id', newId);
                }
            });
            row.querySelectorAll('label[for]').forEach(label => { // These labels are not directly used in table cells but keep for robustness if they were
                const currentFor = label.getAttribute('for');
                const newFor = currentFor.replace(/_(\d+|TEMP)/, `_${i}`);
                label.setAttribute('for', newFor);
            });
        });
    }

    addButton.addEventListener('click', function() {
        const tempTbody = document.createElement('tbody'); // Create a temporary tbody to parse the row string
        tempTbody.innerHTML = newEntryTemplate.trim().replace(/TEMP_INDEX/g, entryIndex).replace(/_TEMP/g, '_' + entryIndex);
        const newEntry = tempTbody.firstChild; // This will be the <tr> element

        container.appendChild(newEntry);
        entryIndex++; // Increment for the next new entry
        updateRemoveButtonsVisibility();
        reindexEntries(); // Ensure all indices are correct after cloning
    });

    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-medicine-entry')) {
            const rowToRemove = event.target.closest('.medicine-entry-row');
            const medicineId = event.target.dataset.medicineId;
            const batchId = event.target.dataset.batchId;

            if (medicineId) { // This is an existing medicine, needs server-side removal
                if (confirm('Are you sure you want to permanently remove this existing medicine from the batch? This cannot be undone by just cancelling the form.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('batches.medicines.remove', [$batch->id, 'PLACEHOLDER_MEDICINE_ID']) }}`.replace('PLACEHOLDER_MEDICINE_ID', medicineId);
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            } else { // This is a newly added row not yet saved to the database
                if (container.children.length > 1) { // Prevent removing the last row if it's the only one
                    rowToRemove.remove();
                    // We don't decrement entryIndex here, as it tracks the *next* available index.
                    // Instead, reindex all visible entries to maintain sequential numbers.
                    reindexEntries();
                    updateRemoveButtonsVisibility();
                } else {
                    alert('You cannot remove the last medicine entry. Please add at least one medicine to the batch.');
                }
            }
        }
    });

    // Initial calls on page load
    updateRemoveButtonsVisibility();
    reindexEntries(); // Ensure all existing entries are correctly indexed on load
});
</script>
@endsection